<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SportTypeRequest;
use App\Models\SportType;
use App\Services\SecureFileStorage;

class SportTypeController extends Controller
{
    private const IMAGE_DIRECTORY = 'sport-types';

    public function __construct(private readonly SecureFileStorage $files) {}

    public function index()
    {
        $sportTypes = SportType::latest()->paginate(10);

        return view('admin.sport-types.index', compact('sportTypes'));
    }

    public function create()
    {
        return view('admin.sport-types.create');
    }

    public function store(SportTypeRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $data['version'] = 0;

        if ($request->hasFile('image')) {
            $data['image'] = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
        }

        SportType::create($data);

        return redirect()->route('admin.sport-types.index')
            ->with('success', 'Thêm loại môn thành công.');
    }

    public function show(SportType $sportType)
    {
        return redirect()->route('admin.sport-types.edit', $sportType);
    }

    public function edit(SportType $sportType)
    {
        return view('admin.sport-types.edit', compact('sportType'));
    }

    public function update(SportTypeRequest $request, SportType $sportType)
    {
        $validated = $request->validated();
        $validated['status'] = $request->boolean('status');

        $oldImage = $sportType->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
            $validated['image'] = $newImage;
        }

        $affectedRows = $sportType->updateWithOptimisticLock($validated);

        // Nếu affectedRows = 0 nghĩa là version đã bị thay đổi bởi request khác
        if ($affectedRows === 0) {
            // Cập nhật thất bại: xóa file vừa tải lên, giữ nguyên ảnh cũ.
            $this->files->delete($newImage, self::IMAGE_DIRECTORY);

            return redirect()->back()->with('error', SportType::getOptimisticLockErrorMessage());
        }

        if ($newImage !== null && $oldImage !== $newImage) {
            $this->files->delete($oldImage, self::IMAGE_DIRECTORY);
        }

        $sportType->courts()->get()->each(fn ($court) => $court->shouldBeSearchable() ? $court->searchable() : $court->unsearchable());

        return redirect()->route('admin.sport-types.index')->with('success', 'Đã cập nhật loại môn thể thao.');
    }

    public function destroy(SportType $sportType)
    {
        if ($sportType->courts()->exists() || $sportType->priceRules()->exists()) {
            return redirect()->route('admin.sport-types.index')
                ->with('error', 'Không thể xóa loại môn này vì đang có sân hoặc bảng giá liên kết.');
        }

        try {
            $imagePath = $sportType->image;
            $sportType->delete();
            $this->files->delete($imagePath, self::IMAGE_DIRECTORY);

            return redirect()->route('admin.sport-types.index')
                ->with('success', 'Đã xóa loại môn.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.sport-types.index')
                ->with('error', 'Không thể xóa loại môn này do có dữ liệu ràng buộc.');
        }
    }
}

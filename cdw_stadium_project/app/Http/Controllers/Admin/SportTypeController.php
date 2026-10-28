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

        if ($request->hasFile('image')) {
            $data['image'] = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
        }

        SportType::create($data);

        return redirect()->route('admin.sport-types.index')
            ->with('success', 'Thêm loại môn thành công.');
    }

    public function edit(SportType $sportType)
    {
        return view('admin.sport-types.edit', compact('sportType'));
    }

    public function update(SportTypeRequest $request, SportType $sportType)
    {
        $validated = $request->validated();

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

            return redirect()->back()->with('error', 'Dữ liệu này vừa được một quản trị viên khác cập nhật. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        if ($newImage !== null && $oldImage !== $newImage) {
            $this->files->delete($oldImage, self::IMAGE_DIRECTORY);
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Đã cập nhật loại sân.');
    }

    public function destroy(SportType $sportType)
    {
        $this->files->delete($sportType->image, self::IMAGE_DIRECTORY);

        $sportType->delete();

        return redirect()->route('admin.sport-types.index')
            ->with('success', 'Đã xóa loại môn.');
    }
}

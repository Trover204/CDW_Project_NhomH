<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SportTypeRequest;
use App\Models\SportType;
use Illuminate\Support\Facades\Storage;

class SportTypeController extends Controller
{
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
            $data['image'] = $request->file('image')->store('sport-types', 'public');
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

        if ($request->hasFile('image')) {
            if ($sportType->image) {
                Storage::disk('public')->delete($sportType->image);
            }
            $data['image'] = $request->file('image')->store('sport-types', 'public');
        }
        $validated = $request->validated();
        $currentVersion = $validated['version'];

        // Loại bỏ version khỏi mảng data để tự xử lý tăng ++
        unset($validated['version']);

        // Query update với điều kiện version phải khớp
        $affectedRows = SportType::where('id', $sportType->id)
            ->where('version', $currentVersion)
            ->update(array_merge($validated, [
                'version' => $currentVersion + 1
            ]));

        // Nếu affectedRows = 0 nghĩa là version đã bị thay đổi bởi request khác
        if ($affectedRows === 0) {
            return redirect()->back()->with('error', 'Dữ liệu này vừa được một quản trị viên khác cập nhật. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Đã cập nhật loại sân.');
    }

    public function destroy(SportType $sportType)
    {
        if ($sportType->image) {
            Storage::disk('public')->delete($sportType->image);
        }

        $sportType->delete();

        return redirect()->route('admin.sport-types.index')
            ->with('success', 'Đã xóa loại môn.');
    }
}
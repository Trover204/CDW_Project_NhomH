<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('courts')->latest()->paginate(10);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(FacilityRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        Facility::create($data);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Thêm cơ sở thành công.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(FacilityRequest $request, Facility $facility)
    {
        $validated = $request->validated();
        $currentVersion = $validated['version'];

        // Loại bỏ version khỏi mảng data để tự xử lý tăng ++
        unset($validated['version']);

        // Query update với điều kiện version phải khớp
        $affectedRows = Facility::where('id', $facility->id)
            ->where('version', $currentVersion)
            ->update(array_merge($validated, [
                'version' => $currentVersion + 1
            ]));

        // Nếu affectedRows = 0 nghĩa là version đã bị thay đổi bởi request khác
        if ($affectedRows === 0) {
            return redirect()->back()->with('error', 'Dữ liệu này vừa được một quản trị viên khác cập nhật. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Đã cập nhật bình luận.');
    }

    public function destroy(Facility $facility)
    {
        if ($facility->courts()->exists()) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Không thể xóa cơ sở này vì đang có sân trực thuộc.');
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Đã xóa cơ sở.');
    }
}

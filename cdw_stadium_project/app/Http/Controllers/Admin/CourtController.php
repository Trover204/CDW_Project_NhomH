<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourtRequest;
use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility:id,name', 'sportType:id,name'])->latest()->paginate(10);

        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        $facilities = Facility::where('status', true)->select('id', 'name')->orderBy('name')->get();
        $sportTypes = SportType::where('status', true)->select('id', 'name')->orderBy('name')->get();

        return view('admin.courts.create', compact('facilities', 'sportTypes'));
    }

    public function store(CourtRequest $request)
    {
        $data = $request->validated();
        $data['version'] = 0;

        Court::create($data);

        return redirect()->route('admin.courts.index')
            ->with('success', 'Thêm sân thành công.');
    }

    public function show(Court $court)
    {
        return redirect()->route('admin.courts.edit', $court);
    }

    public function edit(Court $court)
    {
        $facilities = Facility::select('id', 'name')->orderBy('name')->get();
        $sportTypes = SportType::select('id', 'name')->orderBy('name')->get();

        return view('admin.courts.edit', compact('court', 'facilities', 'sportTypes'));
    }

    public function update(CourtRequest $request, Court $court)
    {
        $validated = $request->validated();

        $affectedRows = $court->updateWithOptimisticLock($validated);

        // Nếu affectedRows = 0 nghĩa là version đã bị thay đổi bởi request khác
        if ($affectedRows === 0) {
            return redirect()->back()->with('error', Court::getOptimisticLockErrorMessage());
        }

        return redirect()->route('admin.courts.index')->with('success', 'Đã cập nhật sân.');
    }

    public function destroy(Court $court)
    {
        try {
            $court->delete();

            return redirect()->route('admin.courts.index')
                ->with('success', 'Đã xóa sân.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.courts.index')
                ->with('error', 'Không thể xóa sân này do có dữ liệu ràng buộc (đơn đặt, hình ảnh hoặc bảng giá).');
        }
    }
}

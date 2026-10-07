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
        $courts = Court::with(['facility', 'sportType'])->latest()->paginate(10);

        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        $facilities = Facility::where('status', true)->orderBy('name')->get();
        $sportTypes = SportType::where('status', true)->orderBy('name')->get();

        return view('admin.courts.create', compact('facilities', 'sportTypes'));
    }

    public function store(CourtRequest $request)
    {
        Court::create($request->validated());

        return redirect()->route('admin.courts.index')
            ->with('success', 'Thêm sân thành công.');
    }

    public function edit(Court $court)
    {
        $facilities = Facility::orderBy('name')->get();
        $sportTypes = SportType::orderBy('name')->get();

        return view('admin.courts.edit', compact('court', 'facilities', 'sportTypes'));
    }

    public function update(CourtRequest $request, Court $court)
    {
        $court->update($request->validated());

        return redirect()->route('admin.courts.index')
            ->with('success', 'Cập nhật sân thành công.');
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

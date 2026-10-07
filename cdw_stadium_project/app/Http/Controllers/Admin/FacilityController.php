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
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        $facility->update($data);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Cập nhật cơ sở thành công.');
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

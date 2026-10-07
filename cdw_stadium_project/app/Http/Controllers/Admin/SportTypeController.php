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
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            if ($sportType->image) {
                Storage::disk('public')->delete($sportType->image);
            }
            $data['image'] = $request->file('image')->store('sport-types', 'public');
        }

        $sportType->update($data);

        return redirect()->route('admin.sport-types.index')
            ->with('success', 'Cập nhật thành công.');
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
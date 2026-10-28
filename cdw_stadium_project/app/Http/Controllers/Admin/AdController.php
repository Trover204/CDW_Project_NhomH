<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdRequest;
use App\Models\Ad;
use App\Services\SecureFileStorage;

class AdController extends Controller
{
    private const IMAGE_DIRECTORY = 'ads';

    public function __construct(private readonly SecureFileStorage $files) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ad::latest()->paginate(10);

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
        }

        Ad::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Thêm quảng cáo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdRequest $request, Ad $ad)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->replace($request->file('image'), $ad->image, self::IMAGE_DIRECTORY);
        }

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Cập nhật quảng cáo thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $this->files->delete($ad->image, self::IMAGE_DIRECTORY);

        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Đã xóa quảng cáo.');
    }
}

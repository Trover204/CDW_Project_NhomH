<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Review;
use App\Models\User;

class ReviewController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.comments.index', ['tab' => 'court-reviews']);
    }

    public function create()
    {
        return view('admin.reviews.create', [
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'courts' => Court::select('id', 'name')->orderBy('name')->get(),
            'bookings' => Booking::with(['user:id,name', 'court:id,name'])
                ->whereDoesntHave('review')
                ->latest()
                ->take(100)
                ->get(),
        ]);
    }

    public function store(ReviewRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = $request->boolean('status');

        Review::create($validated);

        return redirect()
            ->route('admin.comments.index', ['tab' => 'court-reviews'])
            ->with('success', 'Đã thêm đánh giá sân.');
    }

    public function show(Review $review)
    {
        return redirect()->route('admin.reviews.edit', $review);
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', [
            'review' => $review,
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'courts' => Court::select('id', 'name')->orderBy('name')->get(),
            'bookings' => Booking::with(['user:id,name', 'court:id,name'])
                ->where(function ($q) use ($review) {
                    $q->whereDoesntHave('review');
                    if ($review->booking_id) {
                        $q->orWhere('id', $review->booking_id);
                    }
                })
                ->latest()
                ->take(100)
                ->get(),
        ]);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $validated = $request->validated();
        $validated['status'] = $request->boolean('status');

        $review->update($validated);

        return redirect()
            ->route('admin.comments.index', ['tab' => 'court-reviews'])
            ->with('success', 'Đã cập nhật đánh giá sân.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.comments.index', ['tab' => 'court-reviews'])
            ->with('success', 'Đã xóa đánh giá sân.');
    }
}



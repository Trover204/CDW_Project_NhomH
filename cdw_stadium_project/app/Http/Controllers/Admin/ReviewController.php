<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.comments.index', ['tab' => 'court-reviews']);
    }

    public function store(ReviewRequest $request)
    {
        Review::create($request->validated());

        return redirect()
            ->route('admin.comments.index', ['tab' => 'court-reviews'])
            ->with('success', 'Đã thêm đánh giá sân.');
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());

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


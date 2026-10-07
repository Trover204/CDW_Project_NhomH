<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Trang "Sân yêu thích của tôi"
    public function index(Request $request)
    {
        $courts = $request->user()
            ->favoriteCourts()
            ->with(['facility', 'sportType'])
            ->latest('court_user_likes.created_at')
            ->paginate(12);

        return view('favorites.index', compact('courts'));
    }

    // Bấm tim: chưa thích thì thêm, đã thích thì bỏ
    public function toggle(Request $request, Court $court)
    {
        $request->user()->favoriteCourts()->toggle($court->id);

        return back()->with('success', 'Đã cập nhật danh sách yêu thích.');
    }
}
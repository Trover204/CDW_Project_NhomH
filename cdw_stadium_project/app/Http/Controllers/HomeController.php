<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // icon SVG theo tên loại môn (khớp tên trong bảng sport_types)
    private const ICONS = [
        'Bóng đá mini' => '<circle cx="12" cy="12" r="9"/><path d="M12 7l4 3-1.5 4.5h-5L8 10z"/>',
        'Cầu lông'     => '<path d="M4 20l6-6M13 4l7 7-9 9-5-5z"/><circle cx="6" cy="18" r="2"/>',
        'Pickleball'   => '<rect x="4" y="9" width="16" height="9" rx="1.5"/><path d="M12 9V4M9 4h6"/>',
    ];

    public function index(Request $request)
    {
        $user = $request->user();

        $likedIds = $user
            ? $user->favoriteCourts()->pluck('courts.id')->all()
            : [];

        $courts = Court::with(['facility', 'sportType'])
            ->latest()
            ->take(12)
            ->get()
            ->map(function ($court) use ($likedIds) {
                // ... giữ nguyên phần còn lại
            });

        return view('web.home', compact('courts'));
    }
}
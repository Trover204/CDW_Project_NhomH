<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Court;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType'])
            ->where('status', '!=', 'inactive')
            ->orderBy('sport_type_id', 'asc')
            ->paginate(12);
        return view('web.courts.index', compact('courts'));
    }

    public function show(Court $court)
    {
        $court->load(['facility', 'sportType', 'images', 'reviews.user']);
        return view('web.courts.show', compact('court'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType'])->where('status', '!=', 'inactive')->paginate(12);
        return view('web.courts.index', compact('courts'));
    }

    public function show(Court $court)
    {
        $court->load(['facility', 'sportType', 'images', 'reviews.user']);
        return view('web.courts.show', compact('court'));
    }
}

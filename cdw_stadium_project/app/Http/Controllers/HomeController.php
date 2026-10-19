<?php

namespace App\Http\Controllers;

use App\Models\Court;

class HomeController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType'])
            ->orderBy('sport_type_id', 'asc')
            ->take(4)
            ->get();
        return view('web.home', compact('courts'));
    }
}

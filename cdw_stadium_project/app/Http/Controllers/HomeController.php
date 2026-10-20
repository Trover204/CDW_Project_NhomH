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
        $sportTypes = \App\Models\SportType::where('status', 1)->take(3)->get();
        $locations = \App\Models\Facility::where('status', 1)
            ->select('district')
            ->whereNotNull('district')
            ->distinct()
            ->get();
        return view('web.home', compact('courts', 'sportTypes', 'locations'));
    }
}

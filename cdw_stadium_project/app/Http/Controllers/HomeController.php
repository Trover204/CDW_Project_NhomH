<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Facility;
use App\Models\Review;
use App\Models\SportType;

class HomeController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType', 'images'])
            ->orderBy('sport_type_id', 'asc')
            ->take(4)
            ->get();
        $sportTypes = SportType::where('status', 1)->take(3)->get();
        $locations = Facility::where('status', 1)
            ->select('district')
            ->whereNotNull('district')
            ->distinct()
            ->get();
        $reviews = Review::with(['user', 'court'])
            ->where('status', 1)
            ->whereNotNull('content')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('web.home', compact('courts', 'sportTypes', 'locations', 'reviews'));
    }
}

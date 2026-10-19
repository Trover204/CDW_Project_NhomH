<?php

namespace App\Http\Controllers;

use App\Models\Court;

class HomeController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType'])->take(4)->get();
        return view('web.home', compact('courts'));
    }
}

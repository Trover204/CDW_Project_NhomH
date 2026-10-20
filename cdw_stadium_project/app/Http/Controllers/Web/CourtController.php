<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['facility', 'sportType'])
            ->where('status', '!=', 'inactive')
            ->orderBy('sport_type_id', 'asc')
            ->paginate(12);

        $sportTypes = \App\Models\SportType::where('status', 1)->get();
        $locations = \App\Models\Facility::where('status', 1)
            ->select('district')
            ->whereNotNull('district')
            ->distinct()
            ->get();

        return view('web.courts.index', compact('courts', 'sportTypes', 'locations'));
    }

    public function search(Request $request)
    {
        $sportTypeId = $request->input('sport_type');
        $location = $request->input('location');
        $date = $request->input('date');

        if (empty($sportTypeId) && empty($location)) {
            return redirect()->route('courts.index');
        }

        $query = Court::with(['facility', 'sportType'])->where('status', '!=', 'inactive');

        if (!empty($sportTypeId)) {
            $query->where('sport_type_id', $sportTypeId);
        }

        if (!empty($location)) {
            $query->whereHas('facility', function ($q) use ($location) {
                $q->where('district', $location);
            });
        }

        $courts = $query->paginate(12)->appends(request()->query());

        $sportTypes = \App\Models\SportType::where('status', 1)->get();
        $locations = \App\Models\Facility::where('status', 1)
            ->select('district')
            ->whereNotNull('district')
            ->distinct()
            ->get();

        return view('web.courts.search', compact('courts', 'sportTypes', 'locations', 'sportTypeId', 'location', 'date'));
    }

    public function show(Court $court)
    {
        $court->load(['facility', 'sportType', 'images', 'reviews.user']);
        return view('web.courts.show', compact('court'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\SportType;

class CourtController extends Controller
{
            public function index(SearchRequest $request)
    {
        $keyword = $request->keyword();
        $sportTypes = SportType::where('status', 1)->get();
        $selectedSportTypes = $request->input('sport_types', []);

        if ($keyword !== '' || !empty($selectedSportTypes)) {
            if ($keyword !== '') {
                $courts = Court::search($keyword)
                    ->query(function ($query) use ($selectedSportTypes) {
                        $query->with(['facility', 'sportType'])->where('status', '!=', 'inactive');
                        if (!empty($selectedSportTypes)) {
                            $query->whereIn('sport_type_id', $selectedSportTypes);
                        }
                    });
            } else {
                $courts = Court::with(['facility', 'sportType'])
                    ->where('status', '!=', 'inactive')
                    ->whereIn('sport_type_id', $selectedSportTypes);
            }
            $courts = $courts->paginate(12)->appends($request->query());
        } else {
            $courts = collect();
            foreach ($sportTypes as $sportType) {
                $typeCourts = Court::with(['facility', 'sportType'])
                    ->where('status', '!=', 'inactive')
                    ->where('sport_type_id', $sportType->id)
                    ->take(4)
                    ->get();
                $courts = $courts->merge($typeCourts);
            }
        }

        $locations = Facility::where('status', 1)
            ->select('district')
            ->whereNotNull('district')
            ->distinct()
            ->get();

        return view('web.courts.index', compact('courts', 'sportTypes', 'locations', 'keyword'));
    }

    public function search(SearchRequest $request)
    {
        $keyword = $request->keyword();

        if ($keyword === '') {
            return redirect()->route('courts.index');
        }

        $courts = Court::search($keyword)
            ->query(fn ($query) => $query->with(['facility', 'sportType'])->where('status', '!=', 'inactive'))
            ->paginate(12)
            ->appends($request->query());

        $news = News::search($keyword)
            ->query(fn ($query) => $query->where('status', 'published')->with('user:id,name')->latest())
            ->paginate(6, 'news_page')
            ->appends($request->query());

        return view('web.courts.search', compact('courts', 'news', 'keyword'));
    }

    public function show(Court $court)
    {
        $court->load(['facility', 'sportType', 'images', 'reviews.user']);

        return view('web.courts.show', compact('court'));
    }
}

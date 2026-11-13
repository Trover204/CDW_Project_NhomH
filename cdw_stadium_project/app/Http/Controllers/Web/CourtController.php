<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\PriceRule;
use App\Models\Product;
use App\Models\SportType;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class CourtController extends Controller
{
    public function index(SearchRequest $request)
    {
        $keyword = $request->keyword();
        $sportTypes = SportType::where('status', 1)->get();
        $selectedSportTypes = $request->input('sport_types', []);

        if ($keyword !== '' || ! empty($selectedSportTypes)) {
            if ($keyword !== '' && ! $request->isNormalSearch()) {
                $searchAfter = $request->searchAfterCursor();
                $courts = Court::search($keyword)
                    ->when(! empty($selectedSportTypes), function ($search) use ($selectedSportTypes) {
                        $search->whereIn('sport_type_id', array_map('intval', $selectedSportTypes));
                    })
                    ->when($searchAfter !== [], fn ($search) => $search->searchAfter($searchAfter))
                    ->query(function ($query) use ($selectedSportTypes) {
                        $query->with(['facility', 'sportType', 'images'])->where('status', '!=', 'inactive');
                        if (! empty($selectedSportTypes)) {
                            $query->whereIn('sport_type_id', $selectedSportTypes);
                        }
                    });
            } else {
                $courts = Court::with(['facility', 'sportType', 'images'])
                    ->where('status', '!=', 'inactive')
                    ->when($keyword !== '', function ($query) use ($keyword) {
                        $like = "%{$keyword}%";
                        $query->where(function ($q) use ($like) {
                            $q->where('name', 'like', $like)
                                ->orWhere('description', 'like', $like)
                                ->orWhereHas('facility', fn ($f) => $f->where('name', 'like', $like)->orWhere('district', 'like', $like)->orWhere('address', 'like', $like))
                                ->orWhereHas('sportType', fn ($s) => $s->where('name', 'like', $like));
                        });
                    })
                    ->when(! empty($selectedSportTypes), function ($query) use ($selectedSportTypes) {
                        $query->whereIn('sport_type_id', $selectedSportTypes);
                    });
            }
            $courts = $courts->paginate(12)->appends($request->query());
        } else {
            $query = null;
            foreach ($sportTypes as $sportType) {
                $subQuery = Court::where('status', '!=', 'inactive')
                    ->where('sport_type_id', $sportType->id)
                    ->take(4);

                if ($query === null) {
                    $query = $subQuery;
                } else {
                    $query->unionAll($subQuery);
                }
            }
            $courts = $query ? $query->with(['facility', 'sportType', 'images'])->get() : collect();
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
        $searchMode = $request->searchMode();
        $activeType = $request->searchType();

        if (! in_array($activeType, ['all', 'courts', 'products', 'news'], true)) {
            $activeType = 'all';
        }

        if ($keyword === '') {
            return redirect()->route('courts.index');
        }

        if ($request->isNormalSearch()) {
            $like = "%{$keyword}%";

            $courts = Court::with(['facility', 'sportType', 'images'])
                ->where('status', '!=', 'inactive')
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhereHas('facility', fn ($f) => $f->where('name', 'like', $like)->orWhere('district', 'like', $like)->orWhere('address', 'like', $like))
                        ->orWhereHas('sportType', fn ($s) => $s->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(12)
                ->appends($request->query());

            $products = Product::where('is_active', true)
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('team', 'like', $like)
                        ->orWhere('season', 'like', $like);
                })
                ->latest()
                ->paginate(8, ['*'], 'products_page')
                ->appends($request->query());

            $news = News::where('status', 'published')
                ->with('user:id,name')
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('content', 'like', $like)
                        ->orWhere('category', 'like', $like);
                })
                ->latest()
                ->paginate(6, ['*'], 'news_page')
                ->appends($request->query());
        } else {
            $searchAfter = $request->searchAfterCursor();

            $courts = Court::search($keyword)
                ->when($searchAfter !== [], fn ($search) => $search->searchAfter($searchAfter))
                ->query(fn ($query) => $query->with(['facility', 'sportType', 'images'])->where('status', '!=', 'inactive'))
                ->paginate(12)
                ->appends($request->query());

            $products = Product::search($keyword)
                ->where('is_active', true)
                ->when($searchAfter !== [], fn ($search) => $search->searchAfter($searchAfter))
                ->query(fn ($query) => $query->where('is_active', true)->latest())
                ->paginate(8, 'products_page')
                ->appends($request->query());

            $news = News::search($keyword)
                ->where('status', 'published')
                ->when($searchAfter !== [], fn ($search) => $search->searchAfter($searchAfter))
                ->query(fn ($query) => $query->where('status', 'published')->with('user:id,name')->latest())
                ->paginate(6, 'news_page')
                ->appends($request->query());
        }

        return view('web.courts.search', compact('courts', 'products', 'news', 'keyword', 'searchMode', 'activeType'));
    }

    public function show(Court $court)
    {
        $court->load(['facility', 'sportType', 'images', 'reviews.user']);

        $selectedDate = request('date', now()->format('Y-m-d'));

        // Lấy danh sách booking đã được đặt (không tính đã hủy/từ chối) của sân trong ngày được chọn
        $bookedSlots = Booking::where('court_id', $court->id)
            ->whereDate('booking_date', $selectedDate)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get(['start_time', 'end_time'])
            ->flatMap(function ($booking) {
                $slots = [];
                $startH = (int) substr((string) $booking->start_time, 0, 2);
                $endH = (int) substr((string) $booking->end_time, 0, 2);
                $endM = (int) substr((string) $booking->end_time, 3, 2);
                if ($endM > 0) {
                    $endH++;
                }
                if ($endH <= $startH) {
                    return [substr((string) $booking->start_time, 0, 5)];
                }
                for ($h = $startH; $h < $endH; $h++) {
                    $slots[] = str_pad((string) $h, 2, '0', STR_PAD_LEFT).':00';
                }

                return $slots;
            })
            ->unique()
            ->values()
            ->all();

        $subscription = null;
        if (Auth::check()) {
            $subscription = Subscription::with('plan')
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->where('expires_at', '>', now())
                ->latest('expires_at')
                ->first();
        }

        $priceRules = PriceRule::where('status', 1)
            ->where('sport_type_id', $court->sport_type_id)
            ->where(fn ($q) => $q->where('court_id', $court->id)->orWhereNull('court_id'))
            ->orderByRaw('court_id IS NULL')
            ->get(['day_type', 'start_time', 'end_time', 'price']);

        return view('web.courts.show', compact('court', 'selectedDate', 'bookedSlots', 'subscription', 'priceRules'));
    }
}

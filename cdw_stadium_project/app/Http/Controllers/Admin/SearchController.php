<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Ad;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index(SearchRequest $request): View
    {
        $keyword = $request->keyword();
        $activeType = $request->searchType();

        $emptyPaginator = fn (string $pageName) => new LengthAwarePaginator([], 0, 8, 1, [
            'path' => $request->url(),
            'pageName' => $pageName,
        ]);

        if ($keyword === '') {
            $courts = $emptyPaginator('courts_page');
            $facilities = $emptyPaginator('facilities_page');
            $products = $emptyPaginator('products_page');
            $news = $emptyPaginator('news_page');
            $sportTypes = $emptyPaginator('sport_types_page');
            $bookings = $emptyPaginator('bookings_page');
            $users = $emptyPaginator('users_page');
            $sales = $emptyPaginator('sales_page');
            $payments = $emptyPaginator('payments_page');
            $plans = $emptyPaginator('plans_page');
            $subscriptions = $emptyPaginator('subscriptions_page');
            $reviews = $emptyPaginator('reviews_page');
            $ads = $emptyPaginator('ads_page');
        } else {
            $courts = Court::search($keyword)
                ->query(fn ($q) => $q->with(['facility', 'sportType', 'images']))
                ->paginate(8, 'courts_page')
                ->appends($request->query());

            $facilities = Facility::search($keyword)
                ->query(fn ($q) => $q->withCount('courts'))
                ->paginate(8, 'facilities_page')
                ->appends($request->query());

            $products = Product::search($keyword)
                ->paginate(8, 'products_page')
                ->appends($request->query());

            $news = News::search($keyword)
                ->query(fn ($q) => $q->with('user:id,name')->latest())
                ->paginate(8, 'news_page')
                ->appends($request->query());

            $sportTypes = SportType::search($keyword)
                ->query(fn ($q) => $q->withCount('courts'))
                ->paginate(8, 'sport_types_page')
                ->appends($request->query());

            $bookings = Booking::search($keyword)
                ->query(fn ($q) => $q->with(['user', 'court'])->latest())
                ->paginate(8, 'bookings_page')
                ->appends($request->query());

            $users = User::search($keyword)
                ->paginate(8, 'users_page')
                ->appends($request->query());

            $sales = Sale::search($keyword)
                ->query(fn ($q) => $q->with(['product', 'user'])->latest())
                ->paginate(8, 'sales_page')
                ->appends($request->query());

            $payments = Payment::search($keyword)
                ->query(fn ($q) => $q->with('user')->latest())
                ->paginate(8, 'payments_page')
                ->appends($request->query());

            $plans = Plan::search($keyword)
                ->paginate(8, 'plans_page')
                ->appends($request->query());

            $subscriptions = Subscription::search($keyword)
                ->query(fn ($q) => $q->with(['user', 'plan'])->latest())
                ->paginate(8, 'subscriptions_page')
                ->appends($request->query());

            $reviews = Review::search($keyword)
                ->query(fn ($q) => $q->with(['user', 'court'])->latest())
                ->paginate(8, 'reviews_page')
                ->appends($request->query());

            $ads = Ad::search($keyword)
                ->paginate(8, 'ads_page')
                ->appends($request->query());
        }

        $counts = [
            'courts' => $courts->total(),
            'facilities' => $facilities->total(),
            'products' => $products->total(),
            'news' => $news->total(),
            'sport_types' => $sportTypes->total(),
            'bookings' => $bookings->total(),
            'users' => $users->total(),
            'sales' => $sales->total(),
            'payments' => $payments->total(),
            'plans' => $plans->total(),
            'subscriptions' => $subscriptions->total(),
            'reviews' => $reviews->total(),
            'ads' => $ads->total(),
        ];
        $counts['all'] = array_sum($counts);

        return view('admin.search', compact(
            'keyword',
            'activeType',
            'courts',
            'facilities',
            'products',
            'news',
            'sportTypes',
            'bookings',
            'users',
            'sales',
            'payments',
            'plans',
            'subscriptions',
            'reviews',
            'ads',
            'counts'
        ));
    }
}

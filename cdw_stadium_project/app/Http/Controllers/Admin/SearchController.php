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
        $searchMode = $request->searchMode();

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
        } elseif ($request->isNormalSearch()) {
            $like = "%{$keyword}%";

            $courts = Court::with(['facility', 'sportType', 'images'])
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhereHas('facility', fn ($f) => $f->where('name', 'like', $like)->orWhere('district', 'like', $like)->orWhere('address', 'like', $like))
                        ->orWhereHas('sportType', fn ($s) => $s->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'courts_page')
                ->appends($request->query());

            $facilities = Facility::withCount('courts')
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('district', 'like', $like)
                        ->orWhere('address', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                })
                ->latest()
                ->paginate(8, ['*'], 'facilities_page')
                ->appends($request->query());

            $products = Product::where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('team', 'like', $like)
                    ->orWhere('season', 'like', $like);
            })
                ->latest()
                ->paginate(8, ['*'], 'products_page')
                ->appends($request->query());

            $news = News::with('user:id,name')
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('content', 'like', $like)
                        ->orWhere('category', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'news_page')
                ->appends($request->query());

            $sportTypes = SportType::withCount('courts')
                ->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->latest()
                ->paginate(8, ['*'], 'sport_types_page')
                ->appends($request->query());

            $bookings = Booking::with(['user', 'court'])
                ->where(function ($q) use ($like) {
                    $q->where('customer_name', 'like', $like)
                        ->orWhere('customer_phone', 'like', $like)
                        ->orWhere('customer_email', 'like', $like)
                        ->orWhere('note', 'like', $like)
                        ->orWhere('status', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like)->orWhere('phone', 'like', $like))
                        ->orWhereHas('court', fn ($c) => $c->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'bookings_page')
                ->appends($request->query());

            $users = User::where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('role', 'like', $like);
            })
                ->latest()
                ->paginate(8, ['*'], 'users_page')
                ->appends($request->query());

            $sales = Sale::with(['product', 'user'])
                ->where(function ($q) use ($like) {
                    $q->where('customer_name', 'like', $like)
                        ->orWhere('customer_phone', 'like', $like)
                        ->orWhere('status', 'like', $like)
                        ->orWhereHas('product', fn ($p) => $p->where('name', 'like', $like))
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'sales_page')
                ->appends($request->query());

            $payments = Payment::with('user')
                ->where(function ($q) use ($like) {
                    $q->where('transaction_code', 'like', $like)
                        ->orWhere('payment_method', 'like', $like)
                        ->orWhere('status', 'like', $like)
                        ->orWhere('note', 'like', $like)
                        ->orWhere('payable_type', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'payments_page')
                ->appends($request->query());

            $plans = Plan::where('name', 'like', $like)
                ->latest()
                ->paginate(8, ['*'], 'plans_page')
                ->appends($request->query());

            $subscriptions = Subscription::with(['user', 'plan'])
                ->where(function ($q) use ($like) {
                    $q->whereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like))
                        ->orWhereHas('plan', fn ($p) => $p->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'subscriptions_page')
                ->appends($request->query());

            $reviews = Review::with(['user', 'court'])
                ->where(function ($q) use ($like) {
                    $q->where('content', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like))
                        ->orWhereHas('court', fn ($c) => $c->where('name', 'like', $like));
                })
                ->latest()
                ->paginate(8, ['*'], 'reviews_page')
                ->appends($request->query());

            $ads = Ad::where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('link_url', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
                ->latest()
                ->paginate(8, ['*'], 'ads_page')
                ->appends($request->query());
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
            'searchMode',
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

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\News;

class NewsController extends Controller
{
    public function index(SearchRequest $request)
    {
        $keyword = $request->keyword();

        if ($keyword !== '') {
            $news = News::search($keyword)
                ->query(fn ($query) => $query->where('status', 'published')->with('user:id,name')->latest())
                ->paginate(12)
                ->appends($request->query());
        } else {
            $news = News::where('status', 'published')
                ->with('user:id,name')
                ->latest()
                ->paginate(12);
        }

        return view('web.news.index', compact('news', 'keyword'));
    }

    public function show($slug)
    {
        $newsItem = News::with('user:id,name')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $newsItem->increment('views');

        // Fetch related news (same category)
        $relatedQuery = News::where('status', 'published')
            ->where('id', '!=', $newsItem->id)
            ->select(['id', 'title', 'slug', 'image', 'category', 'created_at']);

        $relatedNews = collect();
        if ($newsItem->category) {
            $relatedNews = (clone $relatedQuery)
                ->where('category', $newsItem->category)
                ->latest()
                ->take(3)
                ->get();
        }

        // Fallback if no related news found by category
        if ($relatedNews->isEmpty()) {
            $relatedNews = $relatedQuery
                ->latest()
                ->take(3)
                ->get();
        }

        return view('web.news.detail', compact('newsItem', 'relatedNews'));
    }
}

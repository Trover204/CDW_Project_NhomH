<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('web.news.index', compact('news'));
    }

    public function show($slug)
    {
        $newsItem = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $newsItem->increment('views');

        // Fetch related news (same category)
        $relatedNews = collect();
        if ($newsItem->category) {
            $relatedNews = News::where('category', $newsItem->category)
                ->where('id', '!=', $newsItem->id)
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get();
        }

        // Fallback if no related news found by category
        if ($relatedNews->isEmpty()) {
             $relatedNews = News::where('id', '!=', $newsItem->id)
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('web.news.detail', compact('newsItem', 'relatedNews'));
    }
}

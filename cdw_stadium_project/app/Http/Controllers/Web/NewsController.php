<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Web\StoreNewsCommentRequest;
use App\Models\News;

class NewsController extends Controller
{
    public function index(SearchRequest $request)
    {
        $keyword = $request->keyword();

        if ($keyword !== '') {
            $searchAfter = $request->searchAfterCursor();
            $news = News::search($keyword)
                ->where('status', 'published')
                ->when($searchAfter !== [], fn ($search) => $search->searchAfter($searchAfter))
                ->query(fn ($query) => $query
                    ->where('status', 'published')
                    ->with('user:id,name')
                    ->withCount(['comments' => fn ($q) => $q->where('status', 1)])
                    ->latest())
                ->paginate(12)
                ->appends($request->query());
        } else {
            $news = News::where('status', 'published')
                ->with('user:id,name')
                ->withCount(['comments' => fn ($q) => $q->where('status', 1)])
                ->latest()
                ->paginate(12);
        }

        return view('web.news.index', compact('news', 'keyword'));
    }

    public function show($slug)
    {
        $news = News::with([
            'user:id,name,avatar',
            'comments' => fn ($query) => $query
                ->where('status', 1)
                ->with('user:id,name,avatar,role')
                ->latest(),
        ])
            ->withCount(['comments' => fn ($query) => $query->where('status', 1)])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $news->increment('views');

        $newsItem = $news;
        $comments = $news->comments;

        // Fetch related news (same category)
        $relatedQuery = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->withCount(['comments' => fn ($query) => $query->where('status', 1)])
            ->select(['id', 'title', 'slug', 'image', 'category', 'created_at']);

        $relatedNews = collect();
        if ($news->category) {
            $relatedNews = (clone $relatedQuery)
                ->where('category', $news->category)
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

        return view('web.news.detail', compact('news', 'newsItem', 'relatedNews', 'comments'));
    }

    public function storeComment(StoreNewsCommentRequest $request, string $slug)
    {
        $news = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validated();

        $news->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? 5,
            'status' => true,
            'version' => 0,
        ]);

        return redirect()
            ->to(route('news.show', $news->slug).'#news-comments')
            ->with('success', __('Bình luận của bạn đã được đăng thành công!'));
    }
}


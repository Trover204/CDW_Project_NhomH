<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Court;
use App\Models\News;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // R - Danh sách bình luận & đánh giá
    public function index(Request $request)
    {
        $selectedNewsId = $request->input('news_id');
        $selectedStatus = $request->input('status');
        $keyword = trim((string) $request->input('q', ''));
        $activeTab = $request->input('tab', 'news-comments');

        $commentsQuery = Comment::with(['user:id,name,email,avatar', 'news:id,title,slug,category'])
            ->when($selectedNewsId, fn ($q) => $q->where('news_id', $selectedNewsId))
            ->when($selectedStatus !== null && $selectedStatus !== '', fn ($q) => $q->where('status', (int) $selectedStatus))
            ->when($keyword !== '', function ($q) use ($keyword) {
                $like = "%{$keyword}%";
                $q->where(function ($sub) use ($like) {
                    $sub->where('content', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like))
                        ->orWhereHas('news', fn ($n) => $n->where('title', 'like', $like));
                });
            });

        $comments = $commentsQuery->latest()->paginate(10)->withQueryString();

        $courtReviews = Review::with(['user:id,name,email', 'court:id,name'])
            ->when($keyword !== '', function ($q) use ($keyword) {
                $like = "%{$keyword}%";
                $q->where(function ($sub) use ($like) {
                    $sub->where('content', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like))
                        ->orWhereHas('court', fn ($c) => $c->where('name', 'like', $like));
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'reviews_page')
            ->withQueryString();

        $newsList = News::select('id', 'title', 'slug')->latest()->get();

        $stats = [
            'total_news_comments' => Comment::count(),
            'active_news_comments' => Comment::where('status', 1)->count(),
            'hidden_news_comments' => Comment::where('status', 0)->count(),
            'avg_news_rating' => round((float) (Comment::avg('rating') ?? 5.0), 1),
            'total_court_reviews' => Review::count(),
            'avg_court_rating' => round((float) (Review::avg('rating') ?? 5.0), 1),
        ];

        return view('admin.comments.index', compact(
            'comments',
            'courtReviews',
            'newsList',
            'stats',
            'selectedNewsId',
            'selectedStatus',
            'keyword',
            'activeTab'
        ));
    }

    // C - Form thêm
    public function create()
    {
        return view('admin.comments.create', [
            'users' => User::select('id', 'name', 'email')->get(),
            'newsList' => News::select('id', 'title', 'slug')->latest()->get(),
            'courts' => Court::select('id', 'name')->get(),
        ]);
    }

    // C - Lưu
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();
        $validated['rating'] = $validated['rating'] ?? 5;
        $validated['version'] = 0;

        Comment::create($validated);

        return redirect()->route('admin.comments.index')->with('success', 'Đã thêm bình luận.');
    }

    // R - Xem chi tiết (không dùng view riêng, quay về trang sửa)
    public function show(Comment $comment)
    {
        return redirect()->route('admin.comments.edit', $comment);
    }

    // U - Form sửa
    public function edit(Comment $comment)
    {
        $comment->load(['user:id,name,email', 'news:id,title,slug']);

        return view('admin.comments.edit', [
            'comment' => $comment,
            'users' => User::select('id', 'name', 'email')->get(),
            'newsList' => News::select('id', 'title', 'slug')->latest()->get(),
            'courts' => Court::select('id', 'name')->get(),
        ]);
    }

    // U - Cập nhật
    public function update(CommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();
        $validated['version'] = isset($validated['version']) ? (int) $validated['version'] : (int) ($comment->version ?? 0);
        $validated['rating'] = $validated['rating'] ?? ($comment->rating ?? 5);

        $affectedRows = $comment->updateWithOptimisticLock($validated);

        if ($affectedRows === 0) {
            return redirect()->back()->with('error', 'Dữ liệu này vừa được một quản trị viên khác cập nhật. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        return redirect()->route('admin.comments.index')->with('success', 'Đã cập nhật bình luận.');
    }

    // D - Xóa
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')->with('success', 'Đã xóa bình luận.');
    }
}


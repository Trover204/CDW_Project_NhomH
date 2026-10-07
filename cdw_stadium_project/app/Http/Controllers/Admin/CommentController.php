<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Court;
use App\Models\User;

class CommentController extends Controller
{
    // R - Danh sách
    public function index()
    {
        $comments = Comment::with(['user', 'court'])->latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    // C - Form thêm
    public function create()
    {
        return view('admin.comments.create', [
            'users'  => User::all(),
            'courts' => Court::all(),
        ]);
    }

    // C - Lưu
    public function store(CommentRequest $request)
    {
        Comment::create($request->validated());
        return redirect()->route('admin.comments.index')->with('success', 'Đã thêm bình luận.');
    }

    // R - Xem chi tiết (không dùng view riêng, quay về danh sách)
    public function show(Comment $comment)
    {
        return redirect()->route('admin.comments.edit', $comment);
    }

    // U - Form sửa
    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', [
            'comment' => $comment,
            'users'   => User::all(),
            'courts'  => Court::all(),
        ]);
    }

    // U - Cập nhật
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return redirect()->route('admin.comments.index')->with('success', 'Đã cập nhật bình luận.');
    }

    // D - Xóa
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Đã xóa bình luận.');
    }
}
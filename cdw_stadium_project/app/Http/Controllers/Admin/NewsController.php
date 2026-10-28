<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Services\SecureFileStorage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    private const IMAGE_DIRECTORY = 'news';

    public function __construct(private readonly SecureFileStorage $files) {}

    public function index()
    {
        $news = News::with('user:id,name')->latest()->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(NewsRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
        }

        $validated['user_id'] = auth()->id();

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Thêm tin tức thành công.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->replace($request->file('image'), $news->image, self::IMAGE_DIRECTORY);
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Cập nhật tin tức thành công.');
    }

    public function destroy(News $news)
    {
        $this->files->delete($news->image, self::IMAGE_DIRECTORY);

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Đã xóa tin tức.');
    }
}

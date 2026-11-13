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
        $news = News::with('user:id,name')->withCount('comments')->latest()->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(NewsRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = $this->resolveUniqueSlug($validated['slug'] ?? null, $validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->store($request->file('image'), self::IMAGE_DIRECTORY);
        }

        $validated['user_id'] = auth()->id();

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Thêm tin tức thành công.');
    }

    public function show(News $news)
    {
        return redirect()->route('admin.news.edit', $news);
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $validated = $request->validated();

        $validated['slug'] = $this->resolveUniqueSlug($validated['slug'] ?? null, $validated['title'], $news->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->files->replace($request->file('image'), $news->image, self::IMAGE_DIRECTORY);
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Cập nhật tin tức thành công.');
    }

    public function destroy(News $news)
    {
        $imagePath = $news->image;
        $news->delete();
        $this->files->delete($imagePath, self::IMAGE_DIRECTORY);

        return redirect()->route('admin.news.index')->with('success', 'Đã xóa tin tức.');
    }

    protected function resolveUniqueSlug(?string $slugInput, string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($slugInput ?: $title) ?: 'tin-tuc';
        $slug = $baseSlug;
        $counter = 1;

        while (
            News::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

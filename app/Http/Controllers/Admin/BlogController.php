<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService
    ) {
        $this->authorizeResource(Blog::class, 'blog');
    }

    public function index(Request $request): View
    {
        $query = Blog::with(['author', 'categories']);

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::byType('blog')->orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::byType('blog')->pluck('name', 'id');

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated());
        $validated['author_id'] = auth()->id();

        $blog = Blog::create($validated);

        if ($request->filled('category_ids')) {
            $blog->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(Blog $blog): View
    {
        $blog->load(['author', 'categories']);

        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog): View
    {
        $blog->load(['categories']);
        $categories = Category::byType('blog')->pluck('name', 'id');

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated());

        $blog->update($validated);

        if ($request->has('category_ids')) {
            $blog->categories()->sync($request->category_ids ?? []);
        } else {
            $blog->categories()->sync([]);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function applySeoFallbacks(array $validated): array
    {
        $title = $validated['title'] ?? null;
        $excerpt = ! empty($validated['excerpt'])
            ? $validated['excerpt']
            : str(strip_tags($validated['content'] ?? ''))->limit(160)->toString();

        $validated['meta_title'] = ! empty($validated['meta_title'])
            ? $validated['meta_title']
            : $title;

        $validated['meta_description'] = ! empty($validated['meta_description'])
            ? $validated['meta_description']
            : $excerpt;

        $validated['og_image'] = ! empty($validated['og_image'])
            ? $validated['og_image']
            : ($validated['featured_image'] ?? null);

        $validated['og_image_alt'] = ! empty($validated['og_image_alt'])
            ? $validated['og_image_alt']
            : ($validated['featured_image_alt'] ?? null);

        return $validated;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::with(['author', 'categories'])->latest()->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'blog')->pluck('name', 'id');

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $validated['author_id'] = auth()->id();

        $blog = Blog::create($validated);

        if ($request->filled('category_ids')) {
            $blog->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog): View
    {
        $blog->load(['categories']);
        $categories = Category::where('type', 'blog')->pluck('name', 'id');

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,'.$blog->id],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $validated['author_id'] = auth()->id();

        $blog->update($validated);

        if ($request->has('category_ids')) {
            $blog->categories()->sync($request->category_ids ?? []);
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
}

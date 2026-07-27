<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PortfolioItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $items = PortfolioItem::with(['categories'])->latest()->paginate(15);

        return view('admin.portfolio.index', compact('items'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'portfolio')->pluck('name', 'id');

        return view('admin.portfolio.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'client' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $item = PortfolioItem::create($validated);

        if ($request->filled('category_ids')) {
            $item->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item created successfully.');
    }

    public function edit(PortfolioItem $portfolioItem): View
    {
        $portfolioItem->load(['categories']);
        $categories = Category::where('type', 'portfolio')->pluck('name', 'id');

        return view('admin.portfolio.edit', compact('portfolioItem', 'categories'));
    }

    public function update(Request $request, PortfolioItem $portfolioItem): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug,'.$portfolioItem->id],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'client' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $portfolioItem->update($validated);

        if ($request->has('category_ids')) {
            $portfolioItem->categories()->sync($request->category_ids ?? []);
        }

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item updated successfully.');
    }

    public function destroy(PortfolioItem $portfolioItem): RedirectResponse
    {
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item deleted successfully.');
    }
}

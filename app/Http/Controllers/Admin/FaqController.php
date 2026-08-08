<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Faq::class, 'faq');
    }

    public function index(Request $request): View
    {
        $query = Faq::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question->id', 'like', "%{$search}%")
                    ->orWhere('question->en', 'like', "%{$search}%")
                    ->orWhere('answer->id', 'like', "%{$search}%")
                    ->orWhere('answer->en', 'like', "%{$search}%");
            });
        }

        $faqs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create');
    }

    public function store(StoreFaqRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['sort_order'] ?? null)) {
            $validated['sort_order'] = (int) Faq::max('sort_order') + 1;
        }

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function show(Faq $faq): View
    {
        return view('admin.faqs.show', compact('faq'));
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}

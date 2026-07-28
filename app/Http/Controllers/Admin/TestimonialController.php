<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Testimonial::class, 'testimonial');
    }

    public function index(): View
    {
        $testimonials = Testimonial::latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(StoreTestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->validated());

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function show(Testimonial $testimonial): View
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->validated());

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromoRequest;
use App\Http\Requests\Admin\UpdatePromoRequest;
use App\Models\Promo;
use App\Services\PromoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function __construct(
        protected PromoService $promoService
    ) {
        $this->authorizeResource(Promo::class, 'promo');
    }

    public function index(Request $request): View
    {
        $promos = $this->promoService->listAllAdmin($request->only('search', 'status', 'per_page'));

        return view('admin.promos.index', compact('promos'));
    }

    public function create(): View
    {
        return view('admin.promos.create');
    }

    public function store(StorePromoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_highlighted'] = $request->boolean('is_highlighted');
        $data['is_active'] = $request->boolean('is_active', true);

        try {
            $this->promoService->store($data);

            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil dibuat.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Promo $promo): View
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(UpdatePromoRequest $request, Promo $promo): RedirectResponse
    {
        $data = $request->validated();
        $data['is_highlighted'] = $request->boolean('is_highlighted');
        $data['is_active'] = $request->boolean('is_active');

        try {
            $this->promoService->update($promo, $data);

            return redirect()->route('admin.promos.index')
                ->with('success', 'Promo berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $this->promoService->destroy($promo);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    public function highlight(Promo $promo): RedirectResponse
    {
        $this->authorize('update', $promo);

        try {
            $this->promoService->setHighlight($promo);

            return back()->with('success', "Promo '{$promo->title}' berhasil diatur sebagai Highlight utama.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

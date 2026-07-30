<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrashController extends Controller
{
    /**
     * @var array<string, array{label: string, model: class-string, title_field: string}>
     */
    private array $modules = [
        'blogs' => ['label' => 'Blogs', 'model' => Blog::class, 'title_field' => 'title'],
        'portfolio' => ['label' => 'Portfolio', 'model' => PortfolioItem::class, 'title_field' => 'title'],
        'services' => ['label' => 'Services', 'model' => Service::class, 'title_field' => 'title'],
        'testimonials' => ['label' => 'Testimonials', 'model' => Testimonial::class, 'title_field' => 'client_name'],
        'faqs' => ['label' => 'FAQs', 'model' => Faq::class, 'title_field' => 'question'],
        'categories' => ['label' => 'Categories', 'model' => Category::class, 'title_field' => 'name'],
        'team' => ['label' => 'Team Members', 'model' => TeamMember::class, 'title_field' => 'name'],
        'promos' => ['label' => 'Promo & Voucher', 'model' => Promo::class, 'title_field' => 'title'],
    ];

    public function index(Request $request): View
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only Super Admin can access the Recycle Bin.');

        $currentType = $request->query('type', 'blogs');
        if (! array_key_exists($currentType, $this->modules)) {
            $currentType = 'blogs';
        }

        $counts = [];
        foreach ($this->modules as $key => $config) {
            $counts[$key] = $config['model']::onlyTrashed()->count();
        }

        $modelClass = $this->modules[$currentType]['model'];
        $items = $modelClass::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.trash.index', [
            'modules' => $this->modules,
            'currentType' => $currentType,
            'counts' => $counts,
            'items' => $items,
        ]);
    }

    public function restore(Request $request, string $type, int $id): RedirectResponse
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only Super Admin can restore items.');
        abort_unless(array_key_exists($type, $this->modules), 404);

        $modelClass = $this->modules[$type]['model'];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('admin.trash.index', ['type' => $type])
            ->with('success', 'Item successfully restored from Trash.');
    }

    public function forceDelete(Request $request, string $type, int $id): RedirectResponse
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only Super Admin can permanently delete items.');
        abort_unless(array_key_exists($type, $this->modules), 404);

        $modelClass = $this->modules[$type]['model'];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return redirect()->route('admin.trash.index', ['type' => $type])
            ->with('success', 'Item permanently deleted from database.');
    }

    public function emptyTrash(Request $request, string $type): RedirectResponse
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only Super Admin can empty the Trash.');
        abort_unless(array_key_exists($type, $this->modules), 404);

        $modelClass = $this->modules[$type]['model'];
        $modelClass::onlyTrashed()->get()->each->forceDelete();

        return redirect()->route('admin.trash.index', ['type' => $type])
            ->with('success', 'All trashed items in this module have been permanently deleted.');
    }
}

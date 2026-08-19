<?php

namespace App\View;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBreadcrumbs
{
    /**
     * @var array<string, array{label: string, index: string|null, singular: string|null}>
     */
    private array $sections = [
        'dashboard' => ['label' => 'Dashboard', 'index' => 'admin.dashboard', 'singular' => null],
        'blogs' => ['label' => 'Blogs', 'index' => 'admin.blogs.index', 'singular' => 'blog'],
        'services' => ['label' => 'Services', 'index' => 'admin.services.index', 'singular' => 'service'],
        'categories' => ['label' => 'Categories', 'index' => 'admin.categories.index', 'singular' => 'category'],
        'portfolio' => ['label' => 'Portfolio', 'index' => 'admin.portfolio.index', 'singular' => 'portfolio'],
        'testimonials' => ['label' => 'Testimonials', 'index' => 'admin.testimonials.index', 'singular' => 'testimonial'],
        'faqs' => ['label' => 'FAQs', 'index' => 'admin.faqs.index', 'singular' => 'faq'],
        'promos' => ['label' => 'Promo & Voucher', 'index' => 'admin.promos.index', 'singular' => 'promo'],
        'team' => ['label' => 'Team', 'index' => 'admin.team.index', 'singular' => 'team'],
        'page-seo' => ['label' => 'SEO', 'index' => 'admin.page-seo.index', 'singular' => null],
        'media' => ['label' => 'Media', 'index' => 'admin.media.index', 'singular' => 'medium'],
        'settings' => ['label' => 'Settings', 'index' => 'admin.settings.index', 'singular' => null],
        'trash' => ['label' => 'Recycle Bin', 'index' => 'admin.trash.index', 'singular' => null],
        'activity-logs' => ['label' => 'Activity Logs', 'index' => 'admin.activity-logs.index', 'singular' => null],
    ];

    /**
     * Build an array of ['label' => string, 'href' => string|null] items for the given request.
     *
     * @return array<int, array{label: string, href: string|null}>
     */
    public function for(Request $request): array
    {
        $name = $request->route()?->getName() ?? '';

        if (str_starts_with($name, 'profile.')) {
            $crumbs = $this->root();
            $crumbs[] = ['label' => 'My Profile', 'href' => null];

            return $crumbs;
        }

        if (! str_starts_with($name, 'admin.')) {
            return $this->root();
        }

        $parts = explode('.', $name);
        $sectionKey = $parts[1] ?? '';
        $action = $parts[2] ?? 'index';

        $section = $this->sections[$sectionKey] ?? null;

        if ($sectionKey === 'dashboard') {
            return [['label' => 'Dashboard', 'href' => null]];
        }

        if (! $section) {
            return $this->root();
        }

        $crumbs = $this->root();

        if ($section['index'] !== null && $action !== 'index') {
            $crumbs[] = ['label' => $section['label'], 'href' => route($section['index'])];
        }

        $current = $this->currentLabel($request, $sectionKey, $section, $action);

        if ($current !== '') {
            $crumbs[] = ['label' => $current, 'href' => null];
        }

        return $crumbs;
    }

    /**
     * @param  array{label: string, index: string|null, singular: string|null}  $section
     */
    protected function currentLabel(Request $request, string $sectionKey, array $section, string $action): string
    {
        if ($action === 'index') {
            return $section['label'];
        }

        if ($sectionKey === 'page-seo' && in_array($action, ['show', 'edit'], true)) {
            $pageKey = $request->route('page');

            if (is_string($pageKey) && $pageKey !== '') {
                return str($pageKey)->title()->limit(40)->toString();
            }
        }

        if ($action === 'create') {
            return 'New '.Str::singular($section['label']);
        }

        if (in_array($action, ['show', 'edit'], true) && $section['singular'] !== null) {
            $bound = $request->route($section['singular']);

            if ($bound instanceof Model) {
                $label = $bound->title ?? $bound->name ?? null;

                if (is_string($label) && $label !== '') {
                    return str($label)->limit(40)->toString();
                }
            }
        }

        return match ($action) {
            'edit' => 'Edit '.Str::singular($section['label']),
            'reset-password' => 'Reset Password',
            default => Str::title($action),
        };
    }

    /**
     * @return array<int, array{label: string, href: string|null}>
     */
    private function root(): array
    {
        return [['label' => 'Dashboard', 'href' => route('admin.dashboard')]];
    }
}

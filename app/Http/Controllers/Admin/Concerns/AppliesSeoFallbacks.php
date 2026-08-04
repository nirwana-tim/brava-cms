<?php

namespace App\Http\Controllers\Admin\Concerns;

trait AppliesSeoFallbacks
{
    private function applySeoFallbacks(array $validated, string $descriptionField, string $imageField, string $imageAltField): array
    {
        $title = $validated['title'] ?? null;

        if (is_array($title)) {
            foreach (['id', 'en'] as $loc) {
                if (empty($validated['meta_title'][$loc]) && ! empty($title[$loc])) {
                    $validated['meta_title'][$loc] = $title[$loc];
                }
            }
        } elseif (! empty($title) && empty($validated['meta_title'])) {
            $validated['meta_title'] = $title;
        }

        $validated['og_image'] = ! empty($validated['og_image'])
            ? $validated['og_image']
            : ($validated[$imageField] ?? null);

        if (is_array($validated['og_image_alt'] ?? null) || is_array($validated[$imageAltField] ?? null)) {
            foreach (['id', 'en'] as $loc) {
                if (empty($validated['og_image_alt'][$loc])) {
                    $validated['og_image_alt'][$loc] = $validated[$imageAltField][$loc] ?? null;
                }
            }
        } elseif (empty($validated['og_image_alt'])) {
            $validated['og_image_alt'] = $validated[$imageAltField] ?? null;
        }

        return $validated;
    }
}

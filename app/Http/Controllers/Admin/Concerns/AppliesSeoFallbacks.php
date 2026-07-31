<?php

namespace App\Http\Controllers\Admin\Concerns;

/**
 * @param  array<string, mixed>  $validated
 * @return array<string, mixed>
 */
trait AppliesSeoFallbacks
{
    private function applySeoFallbacks(array $validated, string $descriptionField, string $imageField, string $imageAltField): array
    {
        $title = $validated['title'] ?? null;
        $description = ! empty($validated[$descriptionField])
            ? $validated[$descriptionField]
            : str(strip_tags($validated['content'] ?? ''))->limit(160)->toString();

        $validated['meta_title'] = ! empty($validated['meta_title'])
            ? $validated['meta_title']
            : $title;

        $validated['meta_description'] = ! empty($validated['meta_description'])
            ? $validated['meta_description']
            : $description;

        $validated['og_image'] = ! empty($validated['og_image'])
            ? $validated['og_image']
            : ($validated[$imageField] ?? null);

        $validated['og_image_alt'] = ! empty($validated['og_image_alt'])
            ? $validated['og_image_alt']
            : ($validated[$imageAltField] ?? null);

        return $validated;
    }
}

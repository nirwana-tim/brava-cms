<?php

namespace App\Http\Requests\Concerns;

trait NormalizesLocalizedListInputs
{
    /**
     * Rebuild localized list fields into the canonical {id, en} shape, stripping
     * empty rows so validation only sees meaningful content and empty locales are
     * stored as null (enabling fallback to the Indonesian list).
     *
     * @param  array<int, string>  $fields
     */
    protected function normalizeLocalizedListFields(array $fields): void
    {
        $merged = [];

        foreach ($fields as $field) {
            $id = $this->cleanLocalizedList($field, 'id');
            $en = $this->cleanLocalizedList($field, 'en');

            $merged[$field] = [
                'id' => $id !== [] ? $id : null,
                'en' => $en !== [] ? $en : null,
            ];
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }

    /**
     * @return array<int, array{key: string, value: string}|string>
     */
    private function cleanLocalizedList(string $field, string $locale): array
    {
        $rows = $this->input("{$field}.{$locale}");

        if (! is_array($rows)) {
            return [];
        }

        $cleaned = [];

        foreach ($rows as $row) {
            if (is_array($row)) {
                $key = trim((string) ($row['key'] ?? ''));
                $value = trim((string) ($row['value'] ?? ''));

                if ($key !== '' || $value !== '') {
                    $cleaned[] = ['key' => $key, 'value' => $value];
                }
            } else {
                $text = trim((string) $row);

                if ($text !== '') {
                    $cleaned[] = $text;
                }
            }
        }

        return $cleaned;
    }
}

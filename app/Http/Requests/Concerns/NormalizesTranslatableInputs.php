<?php

namespace App\Http\Requests\Concerns;

trait NormalizesTranslatableInputs
{
    protected function normalizeTranslatableFields(array $fieldNames): void
    {
        $merged = [];

        foreach ($fieldNames as $field) {
            $value = $this->input($field);

            if (is_string($value) || is_null($value)) {
                $enVal = $this->input("{$field}_en") ?? $this->input("{$field}.en");
                $idVal = is_string($value) ? $value : $this->input("{$field}.id");

                $merged[$field] = [
                    'id' => $idVal,
                    'en' => $enVal !== '' ? $enVal : null,
                ];
            }
        }

        if (! empty($merged)) {
            $this->merge($merged);
        }
    }
}

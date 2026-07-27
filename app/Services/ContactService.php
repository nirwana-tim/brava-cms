<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ContactService
{
    public function send(array $data): bool
    {
        Log::info('Contact message received', $data);

        return true;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends ApiController
{
    public function __construct(
        private readonly ContactService $service
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $this->service->send($validated);

        return $this->success(
            [],
            'Thank you for your message. We will get back to you soon.',
            201
        );
    }
}

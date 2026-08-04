<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends ApiController
{
    public function __construct(
        private readonly ContactService $service
    ) {}

    public function store(ContactRequest $request): JsonResponse
    {
        $this->service->send($request->validated());

        return $this->success(
            [],
            'Thank you for your message. We will get back to you soon.'
        );
    }
}

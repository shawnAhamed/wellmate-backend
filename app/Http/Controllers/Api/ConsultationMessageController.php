<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationMessageRequest;
use App\Http\Resources\ConsultationMessageResource;
use App\Models\Consultation;
use App\Services\ConsultationMessageService;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Gate;

class ConsultationMessageController extends Controller
{
    public function __construct(private ConsultationMessageService $messageService)
    {
    }

    public function index(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);

        $messages = ConsultationMessageResource::collection($this->messageService->listFor($consultation));

        return ApiResponse::success(['messages' => $messages]);
    }

    public function store(StoreConsultationMessageRequest $request, Consultation $consultation)
    {
        Gate::authorize('view', $consultation);

        $message = $this->messageService->send($consultation, $request->user(), $request->validated('body'));

        return ApiResponse::success(['message' => new ConsultationMessageResource($message)], 'Message sent.', 201);
    }
}

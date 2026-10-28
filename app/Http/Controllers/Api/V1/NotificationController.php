<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EmailNotificationService;
use App\Services\PushNotificationService;
use App\Services\SMSNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => [
                'required',
                'string',
                Rule::in(['email', 'sms', 'push']),
            ],
            'recipient' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        /** @var array{type: 'email'|'sms'|'push', recipient: string, message: string} $validated */
        $serciveClass = match ($validated['type']) {
            'email' => EmailNotificationService::class,
            'sms' => SMSNotificationService::class,
            'push' => PushNotificationService::class
        };

        $service = app($serciveClass);

        $result = $service->notify(
            $validated['recipient'],
            $validated['message'],
        );

        return response()->json([
            'success' => true,
            'data' => [
                'type' => $validated['type'],
                'result' => $result,
            ],
        ]);
    }
}

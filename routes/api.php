<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ReceiveMoney\AcmeBankWebhook;
use App\Http\Controllers\Api\V1\ReceiveMoney\PayTechBankWebhook;
use App\Http\Controllers\Api\V1\SendingMoney;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post(
        '/generateXML',
        [SendingMoney::class, 'generateXML']
    );

    Route::prefix('webhooks')->group(function () {
        Route::post(
            '/PayTechBank',
            [PayTechBankWebhook::class, 'handle']
        );

        Route::post(
            '/AcmeBank',
            [AcmeBankWebhook::class, 'handle']
        );
    });

    Route::post('/notifications/send', [
        NotificationController::class,
        'send',
    ]);
});

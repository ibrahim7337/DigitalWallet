<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SendingMoneyService;
use Illuminate\Http\Request;

class SendingMoney extends Controller
{
    /**
     * Generate XML
     */
    public function generateXML(
        SendingMoneyService $service,
        Request $request
    ): string {
        return $service->generateXML($request);
    }
}

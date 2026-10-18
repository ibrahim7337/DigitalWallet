<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use Saloon\XmlWrangler\XmlWriter;

class SendingMoneyService
{
    /**
     * Generate XML
     */
    public function generateXML(Request $request): string
    {
        $transferReference = $request->input('PaymentRequestMessage.TransferInfo.Reference');
        $transferDate = $request->input('PaymentRequestMessage.TransferInfo.Date');
        $transferAmount = $request->input('PaymentRequestMessage.TransferInfo.Amount');
        $transferCurrency = $request->input('PaymentRequestMessage.TransferInfo.Currency');

        $senderAccountNumber = $request->input('PaymentRequestMessage.SenderInfo.AccountNumber');

        $recevierBankCode = $request->input('PaymentRequestMessage.ReceiverInfo.BankCode');
        $recevierAccountNumber = $request->input('PaymentRequestMessage.ReceiverInfo.AccountNumber');
        $recevierBeneficiaryName = $request->input('PaymentRequestMessage.ReceiverInfo.BeneficiaryName');

        $notes = $request->input('PaymentRequestMessage.Notes');

        $paymentType = $request->input('PaymentRequestMessage.PaymentType');

        $chargeDetails = $request->input('PaymentRequestMessage.ChargeDetails');

        $payload = [
            'TransferInfo' => [
                'Reference' => $transferReference,
                'Date' => $transferDate,
                'Amount' => $transferAmount,
                'Currency' => $transferCurrency,
            ],
            'SenderInfo' => [
                'AccountNumber' => $senderAccountNumber,
            ],
            'ReceiverInfo' => [
                'BankCode' => $recevierBankCode,
                'AccountNumber' => $recevierAccountNumber,
                'BeneficiaryName' => $recevierBeneficiaryName,
            ],
            'Notes' => [
                $notes,
            ],
            'PaymentType' => $paymentType,
            'ChargeDetails' => $chargeDetails,
        ];

        if (! isset($notes)) {
            unset($payload['Notes']);
        }

        if ($paymentType === '99') {
            unset($payload['PaymentType']);
        }

        if ($chargeDetails === 'SHA') {
            unset($payload['ChargeDetails']);
        }

        $writer = new XmlWriter;

        $xml = $writer->write('PaymentRequestMessage', $payload);

        return $xml;
    }
}

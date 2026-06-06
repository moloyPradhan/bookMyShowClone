<?php

namespace App\Services;

use Razorpay\Api\Api;
use Ramsey\Uuid\Uuid;

class PaymentGatewayService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );
    }

    public function createOrder(
        float $totalAmount,
        array $notes = []
    ) {

        return $this->api->order->create([
            'receipt'  => Uuid::uuid7()->toString(),
            'amount'   => (int) round($totalAmount * 100),
            'currency' => 'INR',
            'notes'    => $notes,
        ]);
    }
}

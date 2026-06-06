<?php

namespace App\Services;

use Razorpay\Api\Api;

class PaymentGatewayService
{

    public function createOrder($totalAmount)
    {
        $api = new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );

        $order = $api->order->create([
            'receipt' => Uuid::uuid7()->toString(),
            'amount' => $totalAmount * 100,
            'currency' => 'INR',
        ]);
    }
}

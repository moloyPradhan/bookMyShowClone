<?php

namespace App\Services;

use Razorpay\Api\Api;

use Ramsey\Uuid\Uuid;

class PaymentGatewayService
{

    public function createOrder(float $totalAmount, array $notes = [])
    {
        $api = new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );

        $order = $api->order->create([
            'receipt'  => Uuid::uuid7()->toString(),
            'amount'   => $totalAmount * 100,
            'currency' => 'INR',
            'notes'    => $notes
        ]);

        return $order;
    }
}

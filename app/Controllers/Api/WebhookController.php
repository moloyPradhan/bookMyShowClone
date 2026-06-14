<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\ShowService;

class WebhookController extends BaseApiController
{
    protected ShowService $showService;

    public function __construct()
    {
        $this->showService = new ShowService();
    }

    public function handlePaymentWebhook()
    {
        return $this->execute(function () {
            $rawBody = $this->request->getBody();
            $signature = $this->request->getHeaderLine('X-Razorpay-Signature') ?: $this->request->getHeaderLine('x-razorpay-signature');

            // Parse json input
            $data = json_decode($rawBody, true) ?: $this->request->getJSON(true) ?: [];

            $orderId = null;
            $paymentId = null;

            // Check if it is a Razorpay event structure
            if (isset($data['event']) && isset($data['payload'])) {
                // If signature validation is required and configured
                $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');
                if (!empty($signature) && !empty($webhookSecret)) {
                    try {
                        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
                        $api->utility->verifyWebhookSignature($rawBody, $signature, $webhookSecret);
                    } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
                        return $this->errorResponse('Signature verification failed', [], 400);
                    }
                }

                $event = $data['event'];
                // Only process order.paid or payment.captured
                if ($event === 'order.paid' || $event === 'payment.captured') {
                    if (isset($data['payload']['payment']['entity'])) {
                        $paymentEntity = $data['payload']['payment']['entity'];
                        $orderId = $paymentEntity['order_id'] ?? null;
                        $paymentId = $paymentEntity['id'] ?? null;
                    }
                    
                    if (empty($orderId) && isset($data['payload']['order']['entity'])) {
                        $orderEntity = $data['payload']['order']['entity'];
                        $orderId = $orderEntity['id'] ?? null;
                    }
                }
            } else {
                // Generic JSON webhook input
                $orderId = $data['order_id'] ?? null;
                $paymentId = $data['payment_id'] ?? null;
            }

            if (empty($orderId)) {
                return $this->errorResponse('Order ID is missing or invalid event type', [], 400);
            }

            if (empty($paymentId)) {
                $paymentId = 'pay_webhook_' . uniqid();
            }

            $result = $this->showService->completeBookingWebhook($orderId, $paymentId, $data);

            return $this->successResponse('Webhook processed successfully', $result);
        });
    }
}

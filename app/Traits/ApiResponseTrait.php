<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse(
        string $message = 'Success',
        $data = [],
        int $code = 200
    ) {
        return response()->setJSON([
            'success' => true,
            'statusCode' => $code,
            'message' => $message,
            'data'    => $data,
        ])->setStatusCode($code);
    }

    protected function errorResponse(
        string $message = 'Error',
        $errors = [],
        int $code = 400
    ) {
        return response()->setJSON([
            'success' => false,
            'statusCode' => $code,
            'message' => $message,
            'errors'  => $errors,

        ])->setStatusCode($code);
    }
}

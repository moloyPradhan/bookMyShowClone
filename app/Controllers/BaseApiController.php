<?php

namespace App\Controllers;

use App\Traits\ApiResponseTrait;
use CodeIgniter\RESTful\ResourceController;

use Config\Auth;

class BaseApiController extends ResourceController
{
    use ApiResponseTrait;
    protected Auth $authConfig;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController(
            $request,
            $response,
            $logger
        );

        $this->authConfig = config('Auth');
    }


    protected function execute(callable $callback)
    {
        try {

            return $callback();
        } catch (\Throwable $e) {

            return $this->handleException($e);
        }
    }


    protected function handleException(\Throwable $e)
    {
        $statusCode = $e->getCode();

        if (
            ! is_numeric($statusCode)
            || $statusCode < 100
            || $statusCode > 599
        ) {
            $statusCode = 500;
        }

        $errors = [];

        if ($e instanceof \App\Exceptions\ValidationException) {

            $errors = $e->getErrors();
        }

        return $this->errorResponse(
            $e->getMessage(),
            $errors,
            $statusCode
        );
    }

    protected function jsonData(): array
    {
        $data = $this->request->getJSON(true);

        if (! is_array($data)) {

            throw new \Exception(
                'Invalid JSON request body',
                400
            );
        }

        return $data;
    }

    protected function validateRequest(
        array $data,
        array $rules
    ): void {

        if (! $this->validateData($data, $rules)) {

            throw new \App\Exceptions\ValidationException(
                'Validation failed',
                $this->validator->getErrors(),
                422
            );
        }
    }


    protected function authenticatedUser()
    {
        return service('request')
            ->authenticatedUser ?? null;
    }
}

<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class JwtAuthFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        try {

            $token = $request
                ->getCookie('access_token');

            if (! $token) {

                return service('response')
                    ->setJSON([
                        'success' => false,
                        'statusCode' => 401,
                        'message' => 'Unauthorized',
                        'errors' => [
                            'access_token' => "Access token not available"
                        ]
                    ])
                    ->setStatusCode(401);
            }

            $decoded = decodeJWT($token);

            $userId = $decoded->data->id;

            $userModel = new UserModel();

            $user = $userModel->find($userId);

            if (! $user) {

                return service('response')
                    ->setJSON([
                        'success' => false,
                        'statusCode' => 401,
                        'message' => 'User not found',
                        'errors' => [
                            'user' => "User not found"
                        ]

                    ])
                    ->setStatusCode(401);
            }

            service('request')->authenticatedUser = $user;
        } catch (\Throwable $e) {

            return service('response')
                ->setJSON([
                    'success' => false,
                    'statusCode' => 401,
                    'message' => 'Invalid token',
                    'errors' => [
                        'access_token' => "Invalid token"
                    ]
                ])
                ->setStatusCode(401);
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}

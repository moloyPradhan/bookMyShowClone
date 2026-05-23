<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {

        $user = service('request')
            ->authenticatedUser ?? null;

        if (! $user) {

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

        if (! in_array(
            $user->role,
            $arguments
        )) {

            return service('response')
                ->setJSON([
                    'success' => false,
                    'statusCode' => 403,
                    'message' => 'Forbidden',
                    'errors' => [
                        'access' => "Access denied"
                    ]
                ])
                ->setStatusCode(403);
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}

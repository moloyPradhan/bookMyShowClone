<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\AuthService;
use App\Validation\AuthValidation;

class AuthController extends BaseApiController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        return $this->execute(function () {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                AuthValidation::registerRules()
            );

            $user = $this->authService->register($data);

            return $this->successResponse(
                'User registered successfully',
                $user,
                201
            );
        });
    }


    public function login()
    {
        return $this->execute(function () {
            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                AuthValidation::loginRules()
            );

            $result = $this->authService
                ->login($data);

            $response = $this->successResponse(
                'Login successful',
                [
                    'user' => $result['user'],
                ]
            );

            return $response

                ->setCookie([
                    'name'     => 'access_token',
                    'value'    => $result['access_token'],
                    'expire'   => $this->authConfig->accessTokenExpire,
                    'httponly' => true,
                    'secure'   => $this->authConfig->cookieSecure,
                    'samesite' => $this->authConfig->cookieSameSite,
                    'path'     => '/',
                ])

                ->setCookie([
                    'name'     => 'refresh_token',
                    'value'    => $result['refresh_token'],
                    'expire'   => $this->authConfig->refreshTokenExpire,
                    'httponly' => true,
                    'secure'   => $this->authConfig->cookieSecure,
                    'samesite' => $this->authConfig->cookieSameSite,
                    'path'     => '/',
                ]);
        });
    }

    public function refresh()
    {
        return $this->execute(function () {

            $refreshToken = $this->request
                ->getCookie('refresh_token');

            if (! $refreshToken) {

                throw new \Exception(
                    'Refresh token missing',
                    401
                );
            }

            $result = $this->authService
                ->refreshAccessToken($refreshToken);

            $response = $this->successResponse(
                'Token refreshed',
                []
            );

            return $response

                ->setCookie([
                    'name'     => 'access_token',
                    'value'    => $result['access_token'],
                    'expire'   => $this->authConfig
                        ->accessTokenExpire,
                    'httponly' => true,
                    'secure'   => $this->authConfig
                        ->cookieSecure,
                    'samesite' => $this->authConfig
                        ->cookieSameSite,
                    'path'     => '/',
                ])

                ->setCookie([
                    'name'     => 'refresh_token',
                    'value'    => $result['refresh_token'],
                    'expire'   => $this->authConfig
                        ->refreshTokenExpire,
                    'httponly' => true,
                    'secure'   => $this->authConfig
                        ->cookieSecure,
                    'samesite' => $this->authConfig
                        ->cookieSameSite,
                    'path'     => '/',
                ]);
        });
    }

    public function logout()
    {
        return $this->execute(function () {

            $refreshToken = $this->request
                ->getCookie('refresh_token');

            if ($refreshToken) {

                $this->authService
                    ->logout($refreshToken);
            }

            return $this->successResponse(
                'Logged out successfully'
            )

                ->deleteCookie(
                    'access_token'
                )

                ->deleteCookie(
                    'refresh_token'
                );
        });
    }

    public function logoutAll()
    {
        return $this->execute(function () {

            $user = $this->authenticatedUser();

            $this->authService
                ->logoutAllDevices($user->id);

            return $this->successResponse(
                'Logged out from all devices'
            )

                ->deleteCookie(
                    'access_token'
                )

                ->deleteCookie(
                    'refresh_token'
                );
        });
    }
}

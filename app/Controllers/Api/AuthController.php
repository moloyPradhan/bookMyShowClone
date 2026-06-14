<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\AuthService;
use App\Services\EmailQueueService;
use App\Validation\AuthValidation;

use Google\Client as GoogleClient;


class AuthController extends BaseApiController
{
    protected AuthService $authService;
    protected EmailQueueService $emailQueueService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->emailQueueService = new EmailQueueService();
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

            $this->emailQueueService->push(
                $user->email,
                'Welcome to BookMyShow Clone',
                view(
                    'emails/welcome',
                    [
                        'user' => $user
                    ]
                )
            );

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


    public function googleLogin()
    {
        return $this->execute(function () {

            $data = $this->jsonData();

            if (empty($data['token'])) {
                throw new \Exception('Google token is required', 400);
            }

            // Manually decode the token first to inspect the payload for debugging
            $tokenParts = explode('.', $data['token']);
            if (count($tokenParts) !== 3) {
                throw new \Exception('Google token is malformed (should have 3 parts separated by dots)', 400);
            }

            $decodedPayload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])), true);
            if (!$decodedPayload) {
                throw new \Exception('Failed to base64-decode the Google token payload', 400);
            }

            $clientId = env('GOOGLE_CLIENT_ID');
            $aud = $decodedPayload['aud'] ?? '';
            $iss = $decodedPayload['iss'] ?? '';
            $exp = $decodedPayload['exp'] ?? 0;

            if ($iss !== 'https://accounts.google.com' && $iss !== 'accounts.google.com') {
                throw new \Exception('Google token issuer is invalid. Got: ' . $iss, 400);
            }

            if ($aud !== $clientId) {
                throw new \Exception('Google token client ID (aud) mismatch. Token has: ' . $aud . ', but server expected: ' . $clientId, 400);
            }

            if (time() >= $exp) {
                throw new \Exception('Google token has expired. Token exp: ' . date('Y-m-d H:i:s', $exp) . ', current server time: ' . date('Y-m-d H:i:s'), 400);
            }

            $client = new GoogleClient([
                'client_id' => $clientId
            ]);

            try {
                $payload = $client->verifyIdToken($data['token']);
            } catch (\Throwable $e) {
                throw new \Exception('Cryptographic verification failed: ' . $e->getMessage(), 400);
            }

            if (!$payload) {
                throw new \Exception('Invalid Google token (signature verification failed or internal client error)', 400);
            }

            $payload['role'] = $data['role'];

            $result = $this->authService->googleLogin($payload);

            $response = $this->successResponse(
                'Login successful',
                [
                    'user' => $result['user']
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
}

<?php

namespace App\Services;

use App\Entities\User;
use App\Entities\UserSession;

use App\Models\UserModel;
use App\Models\UserSessionModel;

class AuthService
{
    public function register(array $data)
    {
        $userModel = new UserModel();

        $exists = $userModel
            ->where('email', $data['email'])
            ->first();

        if ($exists) {
            throw new \Exception(
                'Email already exists',
                409
            );
        }

        $user = new User();

        $user->fill([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'      => $data['role'],
        ]);

        $userModel->insert($user);

        return $userModel->find(
            $userModel->getInsertID()
        );
    }

    public function login(array $data)
    {
        $userModel = new UserModel();

        $user = $userModel
            ->where('email', $data['email'])
            ->first();

        if (! $user) {

            throw new \Exception(
                'Invalid credentials',
                401
            );
        }

        if (
            ! password_verify(
                $data['password'],
                $user->password
            )
        ) {

            throw new \Exception(
                'Invalid credentials 2',
                401
            );
        }

        $accessToken = generateAccessToken(
            $user->publicData()
        );

        $refreshToken = generateRefreshToken(
            $user->publicData()
        );

        $sessionModel = new UserSessionModel();

        $session = new UserSession();

        $session->fill([

            'user_id' => $user->id,

            'refresh_token' => password_hash(
                $refreshToken,
                PASSWORD_DEFAULT
            ),

            'ip_address' => service('request')
                ->getIPAddress(),

            'user_agent' => service('request')
                ->getUserAgent()
                ->getAgentString(),

            // 'expires_at' => date(
            //     'Y-m-d H:i:s',
            //     time() + $this->authConfig->refreshTokenExpire
            // ),
            'expires_at' => date(
                'Y-m-d H:i:s',
                time() + 604800
            ),
        ]);

        $sessionModel->insert($session);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'user'          => $user->publicData(),
        ];
    }

    public function refreshAccessToken(
        string $refreshToken
    ) {

        try {

            $decoded = decodeJWT($refreshToken);

            if (
                ! isset($decoded->type)
                || $decoded->type !== 'refresh'
            ) {

                throw new \Exception(
                    'Invalid refresh token',
                    401
                );
            }

            $userId = $decoded->data->id;

            $session = $this->findValidSession(
                $refreshToken,
                $userId
            );

            if (! $session) {

                // throw new \Exception(
                //     'Session not found',
                //     401
                // );

                $sessionModel = new UserSessionModel();

                $sessionModel
                    ->where('user_id', $userId)
                    ->delete();

                throw new \Exception(
                    'Session compromised. Login again.',
                    401
                );
            }

            if (
                strtotime($session->expires_at)
                < time()
            ) {

                throw new \Exception(
                    'Session expired',
                    401
                );
            }

            $userModel = new UserModel();

            $user = $userModel->find($userId);

            if (! $user) {

                throw new \Exception(
                    'User not found',
                    401
                );
            }

            $accessToken = generateAccessToken(
                $user->publicData()
            );

            $newRefreshToken = generateRefreshToken(
                $user->publicData()
            );

            // rotate stored refresh token
            $sessionModel = new UserSessionModel();

            $sessionModel->update(
                $session->id,
                [

                    'refresh_token' => password_hash(
                        $newRefreshToken,
                        PASSWORD_DEFAULT
                    ),

                    'expires_at' => date(
                        'Y-m-d H:i:s',
                        time() + 604800
                    ),
                ]
            );

            return [
                'access_token'  => $accessToken,
                'refresh_token' => $newRefreshToken,
            ];
        } catch (\Throwable $e) {

            throw new \Exception(
                'Invalid or expired refresh token',
                401
            );
        }
    }

    private function findValidSession(
        string $refreshToken,
        string $userId
    ) {

        $sessionModel = new UserSessionModel();

        $sessions = $sessionModel
            ->where('user_id', $userId)
            ->findAll();

        foreach ($sessions as $session) {

            if (
                password_verify(
                    $refreshToken,
                    $session->refresh_token
                )
            ) {

                return $session;
            }
        }

        return null;
    }

    public function logout(string $refreshToken): void
    {

        try {

            $decoded = decodeJWT(
                $refreshToken
            );

            if (
                ! isset($decoded->type)
                || $decoded->type !== 'refresh'
            ) {

                return;
            }

            $userId = $decoded->data->id;

            $session = $this->findValidSession(
                $refreshToken,
                $userId
            );

            if (! $session) {
                return;
            }

            $sessionModel = new UserSessionModel();

            $sessionModel->delete(
                $session->id
            );
        } catch (\Throwable $e) {

            // silent fail
            return;
        }
    }

    public function logoutAllDevices(
        string $userId
    ): void {

        $sessionModel = new UserSessionModel();

        $sessionModel
            ->where('user_id', $userId)
            ->delete();
    }
}

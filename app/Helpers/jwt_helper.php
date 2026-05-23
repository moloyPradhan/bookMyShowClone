<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (! function_exists('generateJWT')) {

    function generateJWT(array $user): string
    {
        $key = env('JWT_SECRET');

        $payload = [

            'iss' => 'bookmyshow-clone',

            'aud' => 'bookmyshow-users',

            'iat' => time(),

            'exp' => time() + (60 * 60 * 24),

            'data' => [

                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ];

        return JWT::encode(
            $payload,
            $key,
            'HS256'
        );
    }
}

function generateAccessToken(array $user): string
{
    $key = env('JWT_SECRET');

    $payload = [

        'type' => 'access',

        'iat' => time(),

        'exp' => time() + (60 * 15),

        'data' => [

            'id'    => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ],
    ];

    return \Firebase\JWT\JWT::encode(
        $payload,
        $key,
        'HS256'
    );
}

function generateRefreshToken(array $user): string
{
    $key = env('JWT_SECRET');

    $payload = [

        'type' => 'refresh',

        'iat' => time(),

        'exp' => time() + (60 * 60 * 24 * 7),

        'data' => [

            'id' => $user['id'],
        ],
    ];

    return \Firebase\JWT\JWT::encode(
        $payload,
        $key,
        'HS256'
    );
}

if (! function_exists('decodeJWT')) {

    function decodeJWT(string $token): object
    {
        return JWT::decode(
            $token,
            new Key(
                env('JWT_SECRET'),
                'HS256'
            )
        );
    }
}

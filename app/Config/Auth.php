<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    public bool $cookieSecure;

    public string $cookieSameSite;

    public int $accessTokenExpire;

    public int $refreshTokenExpire;

    public function __construct()
    {
        parent::__construct();

        $this->cookieSecure = env(
            'COOKIE_SECURE',
            false
        );

        $this->cookieSameSite = env(
            'COOKIE_SAMESITE',
            'Lax'
        );

        $this->accessTokenExpire = (int) env(
            'ACCESS_TOKEN_EXPIRE',
            900
        );

        $this->refreshTokenExpire = (int) env(
            'REFRESH_TOKEN_EXPIRE',
            604800
        );
    }
}
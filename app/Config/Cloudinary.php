<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cloudinary extends BaseConfig
{
    public string $cloudName;

    public string $apiKey;

    public string $apiSecret;

    public function __construct()
    {
        parent::__construct();

        $this->cloudName = env('CLOUDINARY_CLOUD_NAME');
        $this->apiKey = env('CLOUDINARY_API_KEY');
        $this->apiSecret = env('CLOUDINARY_API_SECRET');
    }
}

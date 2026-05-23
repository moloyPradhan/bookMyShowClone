<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class UploadService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $config = config('Cloudinary');

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $config->cloudName,
                'api_key' => $config->apiKey,
                'api_secret' => $config->apiSecret,
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    public function upload(object $file, string $folder = 'uploads')
    {

        if (! $file->isValid()) {
            throw new \Exception(
                'Invalid file upload',
                400
            );
        }

        $uploaded = $this->cloudinary->uploadApi()
            ->upload(
                $file->getTempName(),
                [
                    'folder' => $folder,
                ]
            );

        return [
            'url' => $uploaded['secure_url'],
            'public_id' => $uploaded['public_id'],
        ];
    }
}

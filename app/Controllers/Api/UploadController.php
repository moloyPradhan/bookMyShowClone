<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\UploadService;

class UploadController extends BaseApiController
{
    protected UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    public function upload()
    {
        return $this->execute(function () {

            $file = $this->request->getFile('file');
            $folder = $this->request->getPost('folder') ?? 'general';

            if (! $file) {
                throw new \Exception(
                    'File is required',
                    400
                );
            }

            $folderPath = 'files/' . $folder;

            $result = $this->uploadService
                ->upload(
                    $file,
                    $folderPath
                );

            return $this->successResponse(
                'File uploaded successfully',
                $result
            );
        });
    }
}

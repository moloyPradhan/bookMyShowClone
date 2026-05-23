<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\ScreenService;
use App\Validation\ScreenValidation;

class ScreenController extends BaseApiController
{
    protected ScreenService $screenService;

    public function __construct()
    {
        $this->screenService = new ScreenService();
    }

    public function createScreen(string $theaterId)
    {
        return $this->execute(function () use ($theaterId) {

            $data = $this->jsonData();

            $data['theater_id'] = $theaterId;

            $this->validateRequest(
                $data,
                ScreenValidation::createRules()
            );

            $screen = $this->screenService
                ->create(
                    $data,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Screen created successfully',
                $screen,
                201
            );
        });
    }

    public function getScreens(string $theaterId)
    {
        return $this->execute(function () use ($theaterId) {

            $screens = $this->screenService->list(
                $theaterId,
                $this->request->getGet(),
                $this->authenticatedUser()
            );

            return $this->successResponse(
                'Screens fetched successfully',
                $screens
            );
        });
    }
}

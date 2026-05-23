<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Validation\TheaterValidation;
use App\Services\TheaterService;

class TheaterController extends BaseApiController
{
    protected TheaterService $theaterService;

    public function __construct()
    {
        $this->theaterService  = new TheaterService();
    }

    public function register()
    {
        return $this->execute(function () {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                TheaterValidation::registerRules()
            );

            $user = $this->authenticatedUser();

            $theater = $this->theaterService->register($data, $user);

            return $this->successResponse(
                'Theater registered successfully',
                $theater,
                201
            );
        });
    }
}

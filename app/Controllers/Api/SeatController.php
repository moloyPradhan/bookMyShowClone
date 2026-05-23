<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\SeatService;
use App\Validation\SeatValidation;

class SeatController extends BaseApiController
{
    protected SeatService $seatService;

    public function __construct()
    {
        $this->seatService = new SeatService();
    }

    public function generate(string $screenId)
    {
        return $this->execute(function () use ($screenId) {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                SeatValidation::generateRules()
            );

            $result = $this->seatService
                ->generateSeats(
                    $screenId,
                    $data,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Seats generated successfully',
                $result,
                201
            );
        });
    }

    public function listSeat(string $screenId)
    {
        return $this->execute(function () use ($screenId) {

            $seats = $this->seatService
                ->list($screenId);

            return $this->successResponse(
                'Seats fetched successfully',
                $seats
            );
        });
    }
}

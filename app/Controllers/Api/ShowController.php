<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Services\ShowService;
use App\Validation\ShowValidation;
use App\Validation\BookingValidation;

class ShowController extends BaseApiController
{
    protected ShowService $showService;

    public function __construct()
    {
        $this->showService = new ShowService();
    }

    public function createShow(string $screenId)
    {
        return $this->execute(function () use ($screenId) {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                ShowValidation::createRules()
            );

            $show = $this->showService
                ->create(
                    $screenId,
                    $data,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Show created successfully',
                $show,
                201
            );
        });
    }

    public function listShow(string $screenId)
    {
        return $this->execute(function () use ($screenId) {

            $shows = $this->showService
                ->list($screenId);

            return $this->successResponse(
                'Shows fetched successfully',
                $shows
            );
        });
    }

    public function seats(string $showId)
    {
        return $this->execute(function () use ($showId) {

            $seats = $this->showService->showSeats($showId);
            return $this->successResponse(
                'Show seats fetched successfully',
                $seats
            );
        });
    }

    public function lockSeats(string $showId)
    {
        return $this->execute(function () use ($showId) {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                BookingValidation::lockSeatsRules()
            );

            $result = $this->showService
                ->lockSeats(
                    $showId,
                    $data['seat_ids'],
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Seats locked successfully',
                $result
            );
        });
    }


    public function createPendingBooking(string $showId)
    {
        return $this->execute(function () use ($showId) {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                BookingValidation::confirmBookingRules()
            );

            $result = $this->showService
                ->createPendingBooking(
                    $showId,
                    $data['seat_ids'],
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Booking confirmed successfully',
                $result,
                201
            );
        });
    }

    public function createBooking(string $showId)
    {
        return $this->execute(function () use ($showId) {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                BookingValidation::confirmBookingRules()
            );

            $result = $this->showService
                ->createBooking(
                    $showId,
                    $data['seat_ids'],
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Booking initated successfully',
                $result,
                201
            );
        });
    }

    public function completeBooking()
    {
        return $this->execute(function () {

            $data = $this->jsonData();

            $result = $this->showService
                ->completeBooking(
                    $data,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Booking confirmed successfully',
                $result
            );
        });
    }

    public function cleanupLocks()
    {
        return $this->execute(function () {

            $result = $this->showService
                ->cleanupExpiredLocks();

            return $this->successResponse(
                'Expired locks cleaned successfully',
                $result
            );
        });
    }
}

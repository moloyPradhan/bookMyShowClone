<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;

use App\Services\BookingService;

class BookingController extends BaseApiController
{
    protected BookingService $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    public function index()
    {
        return $this->execute(function () {

            $bookings = $this->bookingService
                ->myBookings(
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Bookings fetched successfully',
                $bookings
            );
        });
    }

    public function showBooking(string $bookingId)
    {
        return $this->execute(function () use ($bookingId) {

            $booking = $this->bookingService
                ->bookingDetails(
                    $bookingId,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Booking details fetched successfully',
                $booking
            );
        });
     }

    public function cancel(string $bookingId)
    {
        return $this->execute(function () use ($bookingId) {

            $result = $this->bookingService
                ->cancelBooking(
                    $bookingId,
                    $this->authenticatedUser()
                );

            return $this->successResponse(
                'Booking cancelled successfully',
                $result
            );
        });
    }
}


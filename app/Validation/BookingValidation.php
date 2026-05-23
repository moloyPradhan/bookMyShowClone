<?php

namespace App\Validation;

class BookingValidation
{
    public static function lockSeatsRules(): array
    {
        return [

            'seat_ids' => [
                'rules' => 'required',
            ],
        ];
    }


    public static function confirmBookingRules(): array
    {
        return [

            'seat_ids' => [
                'rules' => 'required',
            ],
        ];
    }
}

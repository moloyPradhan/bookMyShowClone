<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ScreenSeat extends Entity
{
    protected $casts = [

        'seat_number' => 'integer',
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];
}
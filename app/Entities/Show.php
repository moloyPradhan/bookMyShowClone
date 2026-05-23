<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Show extends Entity
{
    protected $dates = [

        'start_time',
        'end_time',

        'created_at',
        'updated_at',
    ];

    protected $casts = [

        'price' => 'float',
    ];
}

<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Screen extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'total_seats' => 'integer',
    ];

    protected $dateFormat = 'datetime';
}

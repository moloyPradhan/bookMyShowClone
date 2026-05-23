<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ShowSeat extends Entity
{
    protected $dates = [

        'locked_until',

        'created_at',
        'updated_at',
    ];
}
<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Movie extends Entity
{
    protected $dates = [
        'release_date',
        'created_at',
        'updated_at',
    ];
}

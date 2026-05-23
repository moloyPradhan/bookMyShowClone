<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Theater extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
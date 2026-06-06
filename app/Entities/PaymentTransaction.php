<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PaymentTransaction extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}

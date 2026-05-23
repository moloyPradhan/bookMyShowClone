<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingItemModel extends Model
{
    protected $table = 'booking_items';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'id',
        'booking_id',
        'show_seat_id',
        'price',
    ];

    protected $useTimestamps = true;
}
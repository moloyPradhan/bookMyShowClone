<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'id',
        'user_id',
        'show_id',
        'booking_number',
        'total_amount',
        'status',
    ];

    protected $useTimestamps = true;
}

<?php

namespace App\Models;

use App\Traits\HasUUID;
use CodeIgniter\Model;

class BookingItemModel extends Model
{
    use HasUUID;

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

    protected $beforeInsert = ['generateUUID'];
}
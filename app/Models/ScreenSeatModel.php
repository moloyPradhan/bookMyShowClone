<?php

namespace App\Models;

use App\Entities\ScreenSeat;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class ScreenSeatModel extends Model
{
    use HasUUID;

    protected $table = 'screen_seats';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = ScreenSeat::class;

    protected $allowedFields = [

        'id',
        'screen_id',
        'seat_row',
        'seat_number',
        'seat_label',
        'seat_type',
        'status',
    ];

    protected $useTimestamps = true;

    // protected $beforeInsert = [
    //     'generateUUID',
    // ];
}

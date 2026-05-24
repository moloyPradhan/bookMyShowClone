<?php

namespace App\Models;

use App\Entities\ShowSeat;
use App\Traits\HasUUID;

use CodeIgniter\Model;

class ShowSeatModel extends Model
{
    use HasUUID;

    protected $table = 'show_seats';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = ShowSeat::class;

    protected $allowedFields = [

        'id',

        'show_id',

        'screen_seat_id',

        'status',

        'locked_until',

        'locked_by',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = ['generateUUID'];
}
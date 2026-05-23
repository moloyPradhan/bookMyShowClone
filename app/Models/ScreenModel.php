<?php

namespace App\Models;

use App\Entities\Screen;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class ScreenModel extends Model
{
    use HasUUID;

    protected $table = 'screens';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = Screen::class;

    protected $allowedFields = [

        'id',
        'theater_id',
        'name',
        'type',
        'total_seats',
        'status',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = [
        'generateUUID',
    ];
}
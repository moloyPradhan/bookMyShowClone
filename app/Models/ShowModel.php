<?php

namespace App\Models;

use App\Entities\Show;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class ShowModel extends Model
{
    use HasUUID;
    
    protected $table = 'shows';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = Show::class;

    protected $allowedFields = [

        'id',

        'movie_id',
        'screen_id',

        'start_time',
        'end_time',

        'price',

        'language',

        'format',

        'status',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = [
        'generateUUID',
    ];
}

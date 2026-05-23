<?php

namespace App\Models;

use App\Entities\Theater;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class TheaterModel extends Model
{
    use HasUUID;

    protected $table = 'theaters';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = Theater::class;

    protected $allowedFields = [

        'id',
        'owner_id',

        'name',
        'email',
        'mobile',

        'country',
        'state',
        'city',

        'address_line_1',
        'address_line_2',

        'postal_code',
 
        'latitude',
        'longitude',

        'status',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = [
        'generateUUID',
    ];
}
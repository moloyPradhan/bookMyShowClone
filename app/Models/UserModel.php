<?php

namespace App\Models;

use App\Entities\User;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class UserModel extends Model
{
    use HasUUID;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $returnType = User::class;

    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = ['generateUUID'];
}
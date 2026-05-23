<?php

namespace App\Models;

use App\Entities\UserSession;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class UserSessionModel extends Model
{
    use HasUUID;

    protected $table = 'user_sessions';

    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $returnType = UserSession::class;

    protected $allowedFields = [

        'id',
        'user_id',
        'refresh_token',
        'ip_address',
        'user_agent',
        'expires_at',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = ['generateUUID'];
}

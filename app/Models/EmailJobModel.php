<?php

namespace App\Models;

use App\Entities\EmailJob;
use App\Traits\HasUUID;
use CodeIgniter\Model;


class EmailJobModel extends Model
{
    use HasUUID;

    protected $table = 'email_jobs';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = EmailJob::class;

    protected $allowedFields = [

        'id',
        'to_email',
        'subject',
        'body',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = ['generateUUID'];
}

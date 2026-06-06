<?php

namespace App\Models;

use App\Entities\PaymentTransaction;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class PaymentTransactionModel extends Model
{
    use HasUUID;

    protected $table = 'payment_transactions';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = PaymentTransaction::class;

    protected $allowedFields = [

        'id',
        'uid',
        'payment_id',
        'order_id',
        'purpose',
        'amount',
        'status',
        'payload',
        'webhook_response',
        'success_action',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = [
        'generateUUID',
    ];
}

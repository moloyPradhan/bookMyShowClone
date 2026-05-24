<?php

namespace App\Services;

use App\Models\EmailJobModel;

class EmailQueueService
{
    public function push(
        string $email,
        string $subject,
        string $body
    ): void {

        $model = new EmailJobModel();

        $model->insert([
            'to_email' => $email,
            'subject' => $subject,
            'body' => $body,
            'status' => 'pending',
        ]);
    }
}

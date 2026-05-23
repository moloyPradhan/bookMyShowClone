<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use App\Models\EmailJobModel;

class ProcessEmailQueue extends BaseCommand
{
    protected $group = 'Queue';
    protected $name = 'queue:emails';
    protected $description = 'Process pending email jobs';

    public function run(array $params)
    {
        $emailJobModel = new EmailJobModel();

        $jobs = $emailJobModel
            ->where('status !=', 'sent')
            ->limit(50)
            ->findAll();

        if (empty($jobs)) {

            CLI::write(
                'No pending emails.',
                'yellow'
            );

            return;
        }

        foreach ($jobs as $job) {
            try {

                $email = service('email');

                $email->setTo($job->to_email);
                $email->setSubject($job->subject);
                $email->setMessage($job->body);

                $email->setMailType(
                    'html'
                );

                if (! $email->send()) {
                    throw new \Exception(
                        $email->printDebugger()
                    );
                }

                $emailJobModel->update(
                    $job->id,
                    [
                        'status' => 'sent',
                        'processed_at' => date(
                            'Y-m-d H:i:s'
                        ),
                        'error_message' => null,
                    ]
                );

                CLI::write(
                    "Sent: {$job->to_email}",
                    'green'
                );
            } catch (\Throwable $e) {

                $emailJobModel->update(
                    $job->id,
                    [
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]
                );

                CLI::write(
                    "Failed: {$job->to_email}",
                    'red'
                );
            }
        }
    }
}

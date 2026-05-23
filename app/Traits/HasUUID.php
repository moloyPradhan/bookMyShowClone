<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait HasUUID
{
    protected function generateUUID(array $data)
    {
        if (empty($data['data']['id'])) {

            $data['data']['id']
                = Uuid::uuid7()->toString();
        }

        return $data;
    }
}
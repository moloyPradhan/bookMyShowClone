<?php

use Ramsey\Uuid\Uuid;

if (! function_exists('uuid7')) {

    function uuid7(): string
    {
        return Uuid::uuid7()->toString();
    }
}
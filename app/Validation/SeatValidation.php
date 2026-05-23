<?php

namespace App\Validation;

class SeatValidation
{
    public static function generateRules(): array
    {
        return [

            'rows' => [
                'rules' => 'required|integer|greater_than[0]',
            ],

            'columns' => [
                'rules' => 'required|integer|greater_than[0]',
            ],
        ];
    }
}
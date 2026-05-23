<?php

namespace App\Validation;

class ScreenValidation
{
    public static function createRules(): array
    {
        return [

            'theater_id' => [
                'rules' => 'required',
            ],

            'name' => [
                'rules' => 'required|min_length[2]',
            ],

            'type' => [
                'rules'
                    => 'required|in_list[2D,3D,IMAX,4DX]',
            ],

            'total_seats' => [
                'rules'
                    => 'required|integer|greater_than[0]',
            ],
        ];
    }
}
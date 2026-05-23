<?php

namespace App\Validation;

class ShowValidation
{
    public static function createRules(): array
    {
        return [

            'movie_id' => [
                'rules' => 'required',
            ],

            'start_time' => [
                'rules' => 'required|valid_date',
            ],

            'price' => [
                'rules' => 'required|decimal',
            ],

            'format' => [
                'rules'
                => 'required|in_list[2D,3D,IMAX,4DX]',
            ],
        ];
    }
}

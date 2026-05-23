<?php

namespace App\Validation;

class MovieValidation
{
    public static function createRules(): array
    {
        return [

            'title' => [
                'rules' => 'required|min_length[2]',
            ],

            'duration_minutes' => [
                'rules' => 'required|integer',
            ],

            'language' => [
                'rules' => 'required',
            ],

            'genre' => [
                'rules' => 'required',
            ],

            'status' => [
                'rules'
                    => 'permit_empty|in_list[upcoming,running,inactive]',
            ],
        ];
    }
}
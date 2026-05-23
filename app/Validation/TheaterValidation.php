<?php

namespace App\Validation;

class TheaterValidation
{
    public static function registerRules(): array
    {
        return [

            'name' => [
                'rules' => 'required|min_length[3]',
            ],

            'email' => [
                'rules' => 'required|valid_email|is_unique[theaters.email]',
            ],

            'mobile' => [
                'rules' => 'required|min_length[10]|is_unique[theaters.email]',
            ],

            'country' => [
                'rules' => 'required',
            ],

            'state' => [
                'rules' => 'required',
            ],

            'city' => [
                'rules' => 'required',
            ],

            'address_line_1' => [
                'rules' => 'required',
            ],

            'postal_code' => [
                'rules' => 'required',
            ],
        ];
    }
}

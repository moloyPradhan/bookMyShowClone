<?php

namespace App\Validation;

class AuthValidation
{
    public static function registerRules(): array
    {
        return [

            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
            ],

            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
            ],

            'password' => [
                'rules' => 'required|min_length[6]',
            ],

        ];
    }

    public static function loginRules(): array
    {
        return [

            'email' => [
                'rules' => 'required|valid_email',
            ],

            'password' => [
                'rules' => 'required',
            ],
        ];
    }
}

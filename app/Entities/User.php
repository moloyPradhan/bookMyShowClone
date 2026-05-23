<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class   User extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | Password Mutator
    |--------------------------------------------------------------------------
    | Automatically hashes password
    */
    public function setPassword(string $password)
    {
        $this->attributes['password']
            = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Full Name Accessor Example
    |--------------------------------------------------------------------------
    */
    public function getDisplayName()
    {
        return ucfirst($this->attributes['name']);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic Example
    |--------------------------------------------------------------------------
    */
    public function isAdmin()
    {
        return $this->attributes['role'] === 'admin';
    }

    public function publicData(): array
    {
        $data = $this->toArray();

        unset($data['password']);

        return $data;
    }
}

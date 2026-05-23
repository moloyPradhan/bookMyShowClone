<?php

namespace App\Services;

use App\Entities\Theater;
use App\Models\TheaterModel;

class TheaterService
{
    public function register(
        array $data,
        object $user
    ) {

        if ($user->role !== 'theater_owner') {

            throw new \Exception(
                'Only theater owners allowed',
                403
            );
        }

        $theaterModel = new TheaterModel();

        // $exists = $theaterModel
        //     ->where('owner_id', $user->id)
        //     ->first();

        // if ($exists) {
        //     throw new \Exception(
        //         'Theater already exists',
        //         409
        //     );
        // }

        $theater = new Theater();

        $theater->fill([

            'owner_id' => $user->id,

            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],

            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],

            'address_line_1'  => $data['address_line_1'],

            'address_line_2'  => $data['address_line_2'] ?? null,

            'postal_code'  => $data['postal_code'],

            'latitude'  => $data['latitude'] ?? null,

            'longitude'  => $data['longitude'] ?? null,

            'status' => 'pending',
        ]);

        $theaterModel->insert($theater);

        return $theaterModel->find(
            $theater->id
        );
    }
}

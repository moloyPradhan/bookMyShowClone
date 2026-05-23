<?php

namespace App\Services;

use App\Entities\Screen;
use App\Models\ScreenModel;
use App\Models\TheaterModel;

class ScreenService
{
    public function create(
        array $data,
        object $user
    ) {

        $theaterModel = new TheaterModel();

        $theater = $theaterModel
            ->where('id', $data['theater_id'])
            ->where('owner_id', $user->id)
            ->first();

        if (! $theater) {
            throw new \Exception(
                'Theater not found or unauthorized',
                403
            );
        }

        $screenModel = new ScreenModel();

        $exists = $screenModel
            ->where('theater_id', $data['theater_id'])
            ->where('name', $data['name'])
            ->first();

        if ($exists) {
            throw new \Exception(
                'Screen already exists',
                409
            );
        }

        $screen = new Screen();

        $screen->fill([

            'theater_id' => $data['theater_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'total_seats' => $data['total_seats'],
            'status' => 'active',
        ]);

        $screenModel->insert($screen);

        return $screen;
    }

    public function list(
        string $theaterId,
        array $params,
        mixed $user
    ) {

        $screenModel = new ScreenModel();

        $builder = $screenModel
            ->select('screens.*')

            ->join(
                'theaters',
                'theaters.id = screens.theater_id'
            );

        if (! empty($theaterId)) {
            $builder = $builder->where(
                'screens.theater_id',
                $theaterId
            );
        }

        return $builder
            ->orderBy('screens.created_at', 'DESC')
            ->findAll();
    }
}

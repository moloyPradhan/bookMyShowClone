<?php

namespace App\Services;

use App\Entities\Movie;
use App\Models\MovieModel;

use App\Models\ShowModel;

class MovieService
{
    public function create(array $data)
    {
        $movieModel = new MovieModel();

        $slug = movieSlug($data['title']);

        $exists = $movieModel
            ->where('slug', $slug)
            ->first();

        if ($exists) {

            throw new \Exception(
                'Movie already exists',
                409
            );
        }

        $movie = new Movie();

        $movie->fill([

            'title' => $data['title'],

            'slug' => $slug,

            'description' => $data['description'] ?? null,

            'duration_minutes' => $data['duration_minutes'],

            'language' => $data['language'],

            'genre' => $data['genre'],

            'release_date' => $data['release_date'] ?? null,

            'poster_url' => $data['poster_url'] ?? null,

            'banner_url'
            => $data['banner_url'] ?? null,

            'trailer_url' => $data['trailer_url'] ?? null,

            'censor_rating' => $data['censor_rating'] ?? null,

            'status' => $data['status'] ?? 'upcoming',
        ]);

        $movieModel->insert($movie);

        return $movieModel->find(
            $movie->id
        );
    }


    public function list(array $params = [])
    {
        $movieModel = new MovieModel();

        $builder = $movieModel;

        if (! empty($params['status'])) {

            $builder = $builder->where(
                'status',
                $params['status']
            );
        }

        if (! empty($params['search'])) {

            $builder = $builder->like(
                'title',
                $params['search']
            );
        }

        $perPage = (int) ($params['per_page'] ?? 10);
        $page = (int) ($params['page'] ?? 1);

        $movies = $builder
            ->orderBy('release_date', 'ASC')
            ->paginate(
                $perPage,
                'default',
                $page
            );

        return [
            'items' => $movies,
            'pagination' => [
                'current_page' => $movieModel->pager->getCurrentPage(),
                'per_page' => $perPage,
                'total' => $movieModel->pager->getTotal(),
                'last_page' => $movieModel->pager->getPageCount(),
                'has_next' => $movieModel->pager->hasMore(),
            ],
        ];
    }

    public function shows(
        string $movieId,
        array $params = []
    ) {

        $showModel = new ShowModel();

        $date = $params['date']
            ?? date('Y-m-d');

        $builder = $showModel

            ->select('
                shows.id,
                shows.start_time,
                shows.end_time,
                shows.price,
                shows.language,
                shows.format,

                screens.id as screen_id,
                screens.name as screen_name,
                screens.type as screen_type,

                theaters.id as theater_id,
                theaters.name as theater_name,
                theaters.city,
                theaters.address_line_1,
                theaters.address_line_2,
                theaters.postal_code,
            ')
            ->join(
                'screens',
                'screens.id = shows.screen_id'
            )
            ->join(
                'theaters',
                'theaters.id = screens.theater_id'
            )
            ->where(
                'shows.movie_id',
                $movieId
            )
            ->where(
                'DATE(shows.start_time)',
                $date
            )
            ->where(
                'shows.status',
                'active'
            )
            ->orderBy(
                'shows.start_time',
                'ASC'
            );

        if (!empty($params['theater_id'])) {
            $builder->where('theater_id', $params['theater_id']);
        }

        if (! empty($params['city'])) {
            $builder->where(
                'LOWER(theaters.city)',
                strtolower(
                    $params['city']
                )
            );
        }

        $shows = $builder->findAll();

        $grouped = [];

        foreach ($shows as $show) {
            $theaterId = $show->theater_id;
            if (! isset($grouped[$theaterId])) {
                $grouped[$theaterId] = [

                    'theater_id' => $show->theater_id,
                    'theater_name' => $show->theater_name,
                    'city' => $show->city,
                    'address_line_1' => $show->address_line_1,
                    'address_line_2' => $show->address_line_2,
                    'postal_code' => $show->postal_code,
                    'shows' => [],
                ];
            }

            $grouped[$theaterId]['shows'][] = [

                'show_id' => $show->id,
                'screen_name' => $show->screen_name,
                'screen_type' => $show->screen_type,
                'start_time' => $show->start_time,
                'end_time' => $show->end_time,
                'language' => $show->language,
                'format' => $show->format,
                'price' => $show->price,
            ];
        }

        return array_values($grouped);
    }
}

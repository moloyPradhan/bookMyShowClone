<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;

use App\Services\MovieService;

use App\Validation\MovieValidation;

class MovieController extends BaseApiController
{
    protected MovieService $movieService;

    public function __construct()
    {
        $this->movieService = new MovieService();
    }

    public function create()
    {
        return $this->execute(function () {

            $data = $this->jsonData();

            $this->validateRequest(
                $data,
                MovieValidation::createRules()
            );

            $movie = $this->movieService
                ->create($data);

            return $this->successResponse(
                'Movie created successfully',
                $movie,
                201
            );
        });
    }

    public function index()
    {
        return $this->execute(function () {

            $movies = $this->movieService
                ->list(
                    $this->request->getGet()
                );

            return $this->successResponse(
                'Movies fetched successfully',
                $movies
            );
        });
    }

    public function movieShows(string $movieId)
    {
        return $this->execute(function () use ($movieId) {

            $shows = $this->movieService
                ->shows(
                    $movieId,
                    $this->request->getGet()
                );

            return $this->successResponse(
                'Movie shows fetched successfully',
                $shows
            );
        });
    }
}

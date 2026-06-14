<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function login(): string
    {
        return view('login');
    }

    public function adminDashboard(): string
    {
        return view('admin/dashboard', ['activePage' => 'overview']);
    }

    public function adminAddMovie(): string
    {
        return view('admin/add_movie', ['activePage' => 'add-movie']);
    }

    public function adminListMovies(): string
    {
        return view('admin/list_movies', ['activePage' => 'list-movies']);
    }

    public function adminCleanup(): string
    {
        return view('admin/cleanup', ['activePage' => 'system']);
    }

    public function theaterOwner(): string
    {
        return view('theater_owner');
    }
}

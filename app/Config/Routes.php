<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'DashboardController::login');
$routes->get('admin', 'DashboardController::adminDashboard');
$routes->get('admin/movies', 'DashboardController::adminListMovies');
$routes->get('admin/movies/add', 'DashboardController::adminAddMovie');
$routes->get('admin/cleanup', 'DashboardController::adminCleanup');
$routes->get('theater-owner', 'DashboardController::theaterOwner');

$routes->options('api/(:any)', static function () {
    return response()->setStatusCode(200);
});

// $routes->options('(:any)', static function () {
//     return response()->setStatusCode(200);
// });

$routes->group('api', function ($routes) {

    $routes->post('register', 'Api\AuthController::register');
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('logout', 'Api\AuthController::logout');
    $routes->post('refresh', 'Api\AuthController::refresh');

    $routes->post('login/google', 'Api\AuthController::googleLogin');

    $routes->post('webhooks/payment', 'Api\WebhookController::handlePaymentWebhook');

    $routes->group('movies', function ($routes) {
        $routes->get(
            '/',
            'Api\MovieController::index'
        );

        $routes->get(
            '(:segment)/shows',
            'Api\MovieController::movieShows/$1'
        );
    });

    $routes->group('theaters', function ($routes) {
        $routes->get(
            '(:segment)/screens',
            'Api\ScreenController::getScreens/$1'
        );
    });

    $routes->group('screens',  function ($routes) {
        $routes->get(
            '(:segment)/shows',
            'Api\ShowController::listShow/$1'
        );
    });

    $routes->group('shows', function ($routes) {
        $routes->get(
            '(:segment)/seats',
            'Api\ShowController::seats/$1'
        );
    });

    // protected routes
    $routes->group(
        '',
        ['filter' => 'jwtAuth'],
        function ($routes) {
            $routes->get('profile', 'Api\UserController::profile');
            $routes->post('logout-all', 'Api\AuthController::logoutAll');
            $routes->post('upload', 'Api\UploadController::upload');
        }
    );


    $routes->group('theaters', [
        'filter' => [
            'jwtAuth',
            'role:theater_owner'
        ]
    ], function ($routes) {
        $routes->get('/', 'Api\TheaterController::index');
        $routes->post('register', 'Api\TheaterController::register');

        $routes->post(
            '(:segment)/screens',
            'Api\ScreenController::createScreen/$1'
        );
    });

    $routes->group('screens', [
        'filter' => [
            'jwtAuth',
            'role:theater_owner'
        ]
    ], function ($routes) {

        $routes->post(
            '(:segment)/shows',
            'Api\ShowController::createShow/$1'
        );

        $routes->post(
            '(:segment)/seats',
            'Api\SeatController::generate/$1'
        );

        $routes->get(
            '(:segment)/seats',
            'Api\SeatController::listSeat/$1'
        );
    });

    $routes->group('shows', [
        'filter' => [
            'jwtAuth',
        ]
    ], function ($routes) {
        $routes->post(
            '(:segment)/lock-seats',
            'Api\ShowController::lockSeats/$1'
        );

        $routes->post(
            '(:segment)/create-booking',
            'Api\ShowController::createBooking/$1'
        );
    });

    $routes->group('bookings', [
        'filter' => [
            'jwtAuth',
        ]
    ], function ($routes) {
        $routes->post(
            'complete',
            'Api\ShowController::completeBooking/$1'
        );

        $routes->get(
            '',
            'Api\BookingController::index'
        );

        $routes->get(
            '(:segment)',
            'Api\BookingController::showBooking/$1'
        );

        $routes->post(
            '(:segment)/cancel',
            'Api\BookingController::cancel/$1'
        );
    });


    $routes->group('movies', [
        'filter' => [
            'jwtAuth',
            'role:admin'
        ]
    ], function ($routes) {
        $routes->post('/', 'Api\MovieController::create');
    });

    $routes->group('shows', [
        'filter' => [
            'jwtAuth',
            'role:admin'
        ]
    ], function ($routes) {
        $routes->post('cleanup-locks', 'Api\ShowController::cleanupLocks');
    });
});

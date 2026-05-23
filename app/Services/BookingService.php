<?php

namespace App\Services;

use App\Models\BookingModel;

class BookingService
{
    public function myBookings(
        object $user
    ) {

        $bookingModel = new BookingModel();
        return $bookingModel

            ->select('
                bookings.id,
                bookings.booking_number,
                bookings.total_amount,
                bookings.status,
                bookings.created_at,

                movies.title as movie_title,
                movies.poster_url,

                theaters.name as theater_name,

                screens.name as screen_name,

                shows.start_time

            ')

            ->join(
                'shows',
                'shows.id = bookings.show_id'
            )

            ->join(
                'movies',
                'movies.id = shows.movie_id'
            )

            ->join(
                'screens',
                'screens.id = shows.screen_id'
            )

            ->join(
                'theaters',
                'theaters.id = screens.theater_id'
            )

            ->where(
                'bookings.user_id',
                $user->id
            )

            ->orderBy(
                'bookings.created_at',
                'DESC'
            )

            ->findAll();
    }

    public function bookingDetails(
        string $bookingId,
        object $user
    ) {

        $bookingModel = new BookingModel();

        $booking = $bookingModel

            ->select('

                bookings.*,

                movies.title as movie_title,
                movies.description,
                movies.poster_url,
                movies.duration_minutes,

                theaters.name as theater_name,
                theaters.address_line_1,
                theaters.address_line_2,
                theaters.postal_code,

                screens.name as screen_name,

                shows.start_time,
                shows.end_time

            ')

            ->join(
                'shows',
                'shows.id = bookings.show_id'
            )

            ->join(
                'movies',
                'movies.id = shows.movie_id'
            )

            ->join(
                'screens',
                'screens.id = shows.screen_id'
            )

            ->join(
                'theaters',
                'theaters.id = screens.theater_id'
            )

            ->where(
                'bookings.id',
                $bookingId
            )

            ->where(
                'bookings.user_id',
                $user->id
            )

            ->first();

        if (! $booking) {

            throw new \Exception(
                'Booking not found',
                404
            );
        }

        $items = $bookingModel

            ->db

            ->table('booking_items')

            ->select('

                booking_items.price,

                screen_seats.seat_label,
                screen_seats.seat_type

            ')

            ->join(
                'show_seats',
                'show_seats.id = booking_items.show_seat_id'
            )

            ->join(
                'screen_seats',
                'screen_seats.id = show_seats.screen_seat_id'
            )

            ->where(
                'booking_items.booking_id',
                $bookingId
            )

            ->get()

            ->getResultArray();

        $booking['seats'] = $items;

        return $booking;
    }
}

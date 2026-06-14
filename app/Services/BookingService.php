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

    public function cancelBooking(string $bookingId, object $user)
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $bookingModel = new BookingModel();

            // 1. Fetch booking and ensure it exists and belongs to the user
            $booking = $bookingModel
                ->where('id', $bookingId)
                ->where('user_id', $user->id)
                ->first();

            if (!$booking) {
                throw new \Exception('Booking not found or unauthorized', 404);
            }

            // 2. Check if the booking is already cancelled
            if ($booking['status'] === 'cancelled') {
                throw new \Exception('Booking is already cancelled', 400);
            }

            // 3. Fetch the show to check if the show has already started
            $showModel = new \App\Models\ShowModel();
            $show = $showModel->find($booking['show_id']);
            if (!$show) {
                throw new \Exception('Show not found', 404);
            }

            // A booking cannot be cancelled after the show has started
            if (strtotime($show->start_time) <= time()) {
                throw new \Exception('Cannot cancel booking after the show has started', 400);
            }

            // 4. Get show seats associated with the booking
            $bookingItemModel = new \App\Models\BookingItemModel();
            $items = $bookingItemModel
                ->where('booking_id', $bookingId)
                ->findAll();

            $showSeatIds = array_column($items, 'show_seat_id');

            // 5. Update the booking status to 'cancelled'
            $bookingModel->update($bookingId, [
                'status' => 'cancelled'
            ]);

            // 6. Make the show seats available again
            if (!empty($showSeatIds)) {
                $showSeatModel = new \App\Models\ShowSeatModel();
                $showSeatModel
                    ->whereIn('id', $showSeatIds)
                    ->set([
                        'status' => 'available',
                        'locked_until' => null,
                        'locked_by' => null,
                    ])
                    ->update();
            }

            // 7. Update associated payment transactions to 'cancelled'
            $paymentTransactionModel = new \App\Models\PaymentTransactionModel();
            $transactions = $paymentTransactionModel
                ->where('uid', $user->id)
                ->where('status', 'captured')
                ->findAll();

            foreach ($transactions as $txn) {
                $payload = json_decode($txn->payload, true);
                if (isset($payload['show_id']) && $payload['show_id'] === $booking['show_id']) {
                    $txnSeatIds = $payload['seat_ids'] ?? [];
                    if (!empty(array_intersect($txnSeatIds, $showSeatIds))) {
                        $paymentTransactionModel->update($txn->id, [
                            'status' => 'cancelled'
                        ]);
                    }
                }
            }

            if ($db->transStatus() === false) {
                throw new \Exception('Booking cancellation failed', 500);
            }

            $db->transCommit();

            // 8. Emit seat updates so they show as 'available' on all clients in real-time
            if (!empty($showSeatIds)) {
                $socketService = new \App\Services\SocketService();
                $socketService->emitSeatUpdate(
                    $booking['show_id'],
                    $showSeatIds,
                    'available'
                );
            }

            return [
                'booking_id' => $bookingId,
                'status' => 'cancelled',
            ];

        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}


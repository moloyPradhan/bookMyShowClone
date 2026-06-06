<?php

namespace App\Services;

use App\Entities\Show;

use App\Models\MovieModel;
use App\Models\ScreenModel;
use App\Models\ShowModel;

use App\Models\ScreenSeatModel;
use App\Models\ShowSeatModel;

use App\Models\BookingModel;
use App\Models\BookingItemModel;
use App\Models\PaymentTransactionModel;

use Ramsey\Uuid\Uuid;

class ShowService
{

    public function create(
        string $screenId,
        array $data,
        object $user
    ) {

        $db = db_connect();

        $db->transBegin();

        try {

            $screenModel = new ScreenModel();
            $movieModel = new MovieModel();
            $showModel = new ShowModel();

            $screen = $screenModel
                ->select('screens.*')
                ->join(
                    'theaters',
                    'theaters.id = screens.theater_id'
                )
                ->where('screens.id', $screenId)
                ->where(
                    'theaters.owner_id',
                    $user->id
                )
                ->first();

            if (! $screen) {

                throw new \Exception(
                    'Screen not found or unauthorized',
                    403
                );
            }

            $movie = $movieModel
                ->find($data['movie_id']);

            if (! $movie) {

                throw new \Exception(
                    'Movie not found',
                    404
                );
            }

            $startTime = new \DateTime(
                $data['start_time']
            );

            $endTime = clone $startTime;

            $endTime->modify(
                '+' . $movie->duration_minutes . ' minutes'
            );

            $overlap = $showModel
                ->where('screen_id', $screenId)
                ->groupStart()
                ->where(
                    'start_time <',
                    $endTime->format('Y-m-d H:i:s')
                )
                ->where(
                    'end_time >',
                    $startTime->format('Y-m-d H:i:s')
                )
                ->groupEnd()
                ->first();

            if ($overlap) {
                throw new \Exception(
                    'Show timing overlaps existing show',
                    409
                );
            }

            $show = new Show();
            $show->fill([
                'id' =>  Uuid::uuid7()->toString(),
                'movie_id' => $movie->id,
                'screen_id' => $screenId,
                'start_time' => $startTime->format('Y-m-d H:i:s'),
                'end_time' => $endTime->format('Y-m-d H:i:s'),
                'price' => $data['price'],
                'format' => $data['format'],
                'language' => $data['language']
                    ?? $movie->language,
                'status' => 'active',
            ]);

            $showModel->insert($show);

            $this->generateShowSeats($show);

            if ($db->transStatus() === false) {

                throw new \Exception(
                    'Failed to create show',
                    500
                );
            }

            $db->transCommit();

            return $show;
        } catch (\Throwable $e) {

            $db->transRollback();

            throw $e;
        }
    }

    protected function generateShowSeats(
        object $show
    ): void {

        $screenSeatModel = new ScreenSeatModel();
        $showSeatModel = new ShowSeatModel();

        $screenSeats = $screenSeatModel
            ->where(
                'screen_id',
                $show->screen_id
            )
            ->findAll();

        $batch = [];
        foreach ($screenSeats as $seat) {
            $batch[] = [

                'id' => Uuid::uuid7()->toString(),
                'show_id' => $show->id,
                'screen_seat_id' => $seat->id,
                'status' => 'available',
            ];
        }

        if (! empty($batch)) {
            $showSeatModel->insertBatch($batch);
        }
    }

    public function list(
        string $screenId
    ) {

        $showModel = new ShowModel();

        return $showModel
            ->select('shows.*, movies.title as movie_title')
            ->join(
                'movies',
                'movies.id = shows.movie_id'
            )
            ->where('screen_id', $screenId)
            ->orderBy(
                'start_time',
                'ASC'
            )
            ->findAll();
    }


    public function showSeats(string $showId)
    {
        $showSeatModel = new ShowSeatModel();

        $this->releaseExpiredLocks($showId);

        return $showSeatModel
            ->select('
            show_seats.*,
            screen_seats.seat_row,
            screen_seats.seat_number,
            screen_seats.seat_label,
            screen_seats.seat_type
        ')
            ->join(
                'screen_seats',
                'screen_seats.id = show_seats.screen_seat_id'
            )
            ->where(
                'show_seats.show_id',
                $showId
            )
            ->orderBy(
                'screen_seats.seat_row',
                'ASC'
            )
            ->orderBy(
                'screen_seats.seat_number',
                'ASC'
            )
            ->findAll();
    }

    private function releaseExpiredLocks(string $showId): void
    {
        $showSeatModel = new ShowSeatModel();

        $showSeatModel
            ->where('show_id', $showId)
            ->where('status', 'locked')
            ->where('locked_until <', date('Y-m-d H:i:s'))
            ->set([
                'status' => 'available',
                'locked_until' => null,
                'locked_by' => null,
            ])
            ->update();
    }


    public function lockSeats(
        string $showId,
        array $seatIds,
        object $user
    ) {

        $db = db_connect();
        $db->transBegin();

        try {

            $showSeatModel = new ShowSeatModel();

            $this->releaseExpiredLocks($showId);

            $lockUntil = date(
                'Y-m-d H:i:s',
                time() + (5 * 60)
            );

            $seats = $showSeatModel
                ->where('show_id', $showId)
                ->whereIn('id', $seatIds)
                ->findAll();

            if (count($seats) !== count($seatIds)) {
                throw new \Exception(
                    'Some seats not found',
                    404
                );
            }

            foreach ($seats as $seat) {
                $isLocked = (
                    $seat->status === 'locked'
                    &&
                    $seat->locked_until !== null
                    &&
                    strtotime(
                        $seat->locked_until
                    ) > time()
                );

                if (
                    $seat->status === 'booked'
                    || $isLocked
                ) {

                    throw new \Exception(
                        'Some seats already unavailable',
                        409
                    );
                }
            }

            $showSeatModel
                ->whereIn('id', $seatIds)
                ->set([
                    'status' => 'locked',
                    'locked_until' => $lockUntil,
                    'locked_by' => $user->id,
                ])
                ->update();

            if ($db->transStatus() === false) {

                throw new \Exception(
                    'Failed to lock seats',
                    500
                );
            }

            $db->transCommit();

            $socketService = new SocketService();

            $socketService->emitSeatUpdate(
                $showId,
                $seatIds,
                'locked'
            );

            return [
                'locked_until' => $lockUntil,
                'seat_ids' => $seatIds,
            ];
        } catch (\Throwable $e) {

            $db->transRollback();

            throw $e;
        }
    }


    public function createPendingBooking(
        string $showId,
        array $seatIds,
        object $user
    ) {

        $db = db_connect();

        $db->transBegin();

        try {

            $showSeatModel = new ShowSeatModel();
            $bookingModel = new BookingModel();
            $bookingItemModel = new BookingItemModel();

            $showModel = new ShowModel();

            $show = $showModel->find($showId);

            if (! $show) {
                throw new \Exception(
                    'Show not found',
                    404
                );
            }

            $seats = $showSeatModel
                ->where('show_id', $showId)
                ->whereIn('id', $seatIds)
                ->findAll();

            if (
                count($seats)
                !== count($seatIds)
            ) {

                throw new \Exception(
                    'Invalid seats',
                    404
                );
            }

            foreach ($seats as $seat) {

                $lockExpired = (
                    $seat->locked_until === null
                    ||
                    strtotime(
                        $seat->locked_until
                    )
                    < time()
                );

                if ($seat->status !== 'locked' || $seat->locked_by !== $user->id || $lockExpired) {
                    throw new \Exception(
                        'Some seats are not locked by you',
                        409
                    );
                }
            }

            $bookingId = Uuid::uuid7()->toString();

            $bookingNumber = 'BMS-' . strtoupper(
                substr(
                    uniqid(),
                    -8
                )
            );

            $totalAmount = (count($seats) * $show->price);

            $bookingModel->insert([
                'id' => $bookingId,
                'user_id' => $user->id,
                'show_id' => $showId,
                'booking_number' => $bookingNumber,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            $items = [];
            foreach ($seats as $seat) {
                $items[] = [
                    'id' => Uuid::uuid7()->toString(),
                    'booking_id' => $bookingId,
                    'show_seat_id' => $seat->id,
                    'price' => $show->price,
                ];
            }

            $bookingItemModel->insertBatch($items);

            if ($db->transStatus() === false) {
                throw new \Exception(
                    'Booking creation failed',
                    500
                );
            }

            $db->transCommit();

            $socketService = new SocketService();

            $socketService->emitSeatUpdate(
                $showId,
                $seatIds,
                'booked'
            );

            return [
                'booking_id' => $bookingId,
                'booking_number' => $bookingNumber,
                'amount' => $totalAmount,
                'status' => 'pending',
            ];
        } catch (\Throwable $e) {

            $db->transRollback();
            throw $e;
        }
    }

    public function completeBooking(
        array $data,
        object $user
    ) {

        $db = db_connect();

        $db->transBegin();

        try {

            $orderId = $data['order_id'];
            $paymentId = $data['payment_id'];

            $paymentTransactionModel = new PaymentTransactionModel();
            $showModel = new ShowModel();
            $showSeatModel = new ShowSeatModel();
            $bookingModel = new BookingModel();
            $bookingItemModel = new BookingItemModel();

            $transaction = $paymentTransactionModel
                ->where('order_id', $orderId)
                ->first();

            if (! $transaction) {
                throw new \Exception(
                    'Transaction not found',
                    404
                );
            }

            if ($transaction->status === 'captured') {
                throw new \Exception(
                    'Payment already processed',
                    409
                );
            }

            $payload = json_decode(
                $transaction->payload,
                true
            );

            $showId = $payload['show_id'];
            $seatIds = $payload['seat_ids'];

            $show = $showModel->find($showId);

            if (! $show) {
                throw new \Exception(
                    'Show not found',
                    404
                );
            }

            $seats = $showSeatModel
                ->where('show_id', $showId)
                ->whereIn('id', $seatIds)
                ->findAll();

            if (count($seats) !== count($seatIds)) {
                throw new \Exception(
                    'Invalid seats',
                    404
                );
            }

            foreach ($seats as $seat) {

                $lockExpired = (
                    $seat->locked_until === null
                    ||
                    strtotime($seat->locked_until) < time()
                );

                if (
                    $seat->status !== 'locked'
                    || $seat->locked_by !== $user->id
                    || $lockExpired
                ) {

                    throw new \Exception(
                        'Some seats are no longer available',
                        409
                    );
                }
            }

            $bookingId = Uuid::uuid7()->toString();

            $bookingNumber = 'BMS-' . strtoupper(
                substr(
                    uniqid(),
                    -8
                )
            );

            $totalAmount = count($seats) * $show->price;

            $bookingModel->insert([
                'id' => $bookingId,
                'user_id' => $user->id,
                'show_id' => $showId,
                'booking_number' => $bookingNumber,
                'total_amount' => $totalAmount,
                'status' => 'confirmed',
            ]);

            $items = [];

            foreach ($seats as $seat) {

                $items[] = [
                    'id' => Uuid::uuid7()->toString(),
                    'booking_id' => $bookingId,
                    'show_seat_id' => $seat->id,
                    'price' => $show->price,
                ];
            }

            $bookingItemModel->insertBatch(
                $items
            );

            $showSeatModel
                ->whereIn('id', $seatIds)
                ->set([
                    'status' => 'booked',
                    'locked_until' => null,
                    'locked_by' => null,
                ])
                ->update();

            $paymentTransactionModel
                ->update(
                    $transaction->id,
                    [
                        'payment_id' => $paymentId,
                        'status' => 'captured',
                    ]
                );

            if ($db->transStatus() === false) {

                throw new \Exception(
                    'Booking completion failed',
                    500
                );
            }

            $db->transCommit();

            $socketService = new SocketService();

            $socketService->emitSeatUpdate(
                $showId,
                $seatIds,
                'booked'
            );

            return [
                'booking_id' => $bookingId,
                'booking_number' => $bookingNumber,
                'status' => 'confirmed',
                'amount' => $totalAmount,
            ];
        } catch (\Throwable $e) {

            $db->transRollback();

            throw $e;
        }
    }

    public function cleanupExpiredLocks()
    {
        $showSeatModel = new ShowSeatModel();

        $updated = $showSeatModel
            ->where('status', 'locked')
            ->where('locked_until IS NOT NULL')
            ->where(
                'locked_until <',
                date('Y-m-d H:i:s')
            )
            ->set([
                'status' => 'available',
                'locked_until' => null,
                'locked_by' => null,
            ])
            ->update();

        return [
            'cleaned' => $updated,
        ];
    }


    public function createBooking(
        string $showId,
        array $seatIds,
        object $user
    ) {

        $db = db_connect();

        $db->transBegin();

        try {

            $showSeatModel = new ShowSeatModel();
            $showModel = new ShowModel();
            $paymentTransactionModel = new PaymentTransactionModel();

            $show = $showModel->find($showId);

            if (! $show) {
                throw new \Exception(
                    'Show not found',
                    404
                );
            }

            $seats = $showSeatModel
                ->where('show_id', $showId)
                ->whereIn('id', $seatIds)
                ->findAll();

            if (count($seats) !== count($seatIds)) {
                throw new \Exception(
                    'Invalid seats',
                    404
                );
            }

            foreach ($seats as $seat) {

                $lockExpired = (
                    $seat->locked_until === null
                    ||
                    strtotime($seat->locked_until) < time()
                );

                if (
                    $seat->status !== 'locked'
                    || $seat->locked_by !== $user->id
                    || $lockExpired
                ) {

                    throw new \Exception(
                        'Some seats are not locked by you',
                        409
                    );
                }
            }

            $totalAmount = count($seats) * $show->price;

            $notes = [
                'purpose' => 'booking_show',
                'show_id' => $showId,
                'user_id' => $user->id,
                'name'    => $user->name,
                'mobile'  => $user->mobile,
                'email'   => $user->email,
            ];

            $paymentGatewayService = new PaymentGatewayService();

            $order = $paymentGatewayService->createOrder(
                $totalAmount,
                $notes
            );

            $paymentTransactionModel->insert([
                'uid'        => $user->id,
                'order_id'   => $order->id,
                'payment_id' => null,
                'purpose'    => 'booking_show',
                'amount'     => $totalAmount,
                'status'     => 'created',
                'payload'    => json_encode([
                    'show_id'  => $showId,
                    'seat_ids' => $seatIds,
                    'user_id'  => $user->id,
                ]),
                'success_action' => json_encode([
                    'action' => 'create_booking'
                ]),
            ]);

            if ($db->transStatus() === false) {
                throw new \Exception(
                    'Failed to create payment transaction',
                    500
                );
            }

            $db->transCommit();

            return [
                'gateway'  => 'razorpay',
                'key'      => env('RAZORPAY_KEY_ID'),
                'order_id' => $order->id,
                'amount'   => $order->amount,
                'currency' => 'INR',
                'status'   => 'created',
            ];
        } catch (\Throwable $e) {

            $db->transRollback();

            throw $e;
        }
    }
}

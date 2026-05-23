<?php

namespace App\Services;

class SocketService
{
    protected string $socketUrl;

    public function __construct()
    {
        $this->socketUrl = env('socket.serverUrl', 'http://localhost:3001');
    }

    public function emitSeatUpdate(
        string $showId,
        array $seatIds,
        string $status
    ): void {

        try {

            service('curlrequest')->post(
                $this->socketUrl . '/emit-seat-update',
                [
                    'json' => [
                        'show_id' => $showId,
                        'seat_ids' => $seatIds,
                        'status' => $status,
                    ],
                ]
            );
        } catch (\Throwable $e) {

            log_message(
                'error',
                'Socket emit failed: '
                    . $e->getMessage()
            );
        }
    }
}

<?php

namespace App\Services;

use App\Entities\ScreenSeat;

use App\Models\ScreenModel;
use App\Models\ScreenSeatModel;

use App\Traits\HasUUID;

use Ramsey\Uuid\Uuid;

class SeatService
{
    use HasUUID;

    public function generateSeats(
        string $screenId,
        array $data,
        object $user
    ) {

        $screenModel = new ScreenModel();

        $seatModel = new ScreenSeatModel();

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

        $existingSeats = $seatModel
            ->where('screen_id', $screenId)
            ->countAllResults();

        if ($existingSeats > 0) {
            throw new \Exception(
                'Seats already generated',
                409
            );
        }

        $rows = (int) $data['rows'];
        $columns = (int) $data['columns'];
        $batch = [];

        for ($r = 0; $r < $rows; $r++) {

            $rowLetter = chr(65 + $r);
            for ($c = 1; $c <= $columns; $c++) {

                $batch[] = [
                    'id' => Uuid::uuid7()->toString(),
                    'screen_id' => $screenId,
                    'seat_row' => $rowLetter,
                    'seat_number' => $c,
                    'seat_label' => $rowLetter . $c,
                    'seat_type' => 'regular',
                    'status' => 'active',
                ];
            }
        }

        $seatModel->insertBatch($batch);

        return [
            'total_seats' => count($batch),
            'rows' => $rows,
            'columns' => $columns,
        ];
    }

    public function list(
        string $screenId
    ) {

        $seatModel = new ScreenSeatModel();

        return $seatModel

            ->where(
                'screen_id',
                $screenId
            )

            ->orderBy(
                'seat_row',
                'ASC'
            )

            ->orderBy(
                'seat_number',
                'ASC'
            )

            ->findAll();
    }
}

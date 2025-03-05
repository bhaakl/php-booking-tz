<?php

namespace App\Modules\Booking\Services;

use App\Modules\Booking\Repositories\BookingRepository;
use App\Modules\Booking\Models\Booking;

class BookingService
{
    public function __construct(private readonly BookingRepository $repo)
    {
    }

    public function createBooking(
        int $asset_id,
        int $user_id,
        string $start_time,
        string $end_time,
    ) {
        return Booking::create([
        'asset_id' => $asset_id,
        'user_id' => $user_id,
        'start_time' => $start_time,
        'end_time' => $end_time,
        ]);
    }

    public function deleteBooking($id)
    {
        $this->repo->getOne($id)->delete();
    }
}

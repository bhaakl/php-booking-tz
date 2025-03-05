<?php

namespace App\Modules\Booking\Repositories;

use App\Modules\Base\Repositories\Repository;
use App\Modules\Asset\Models\Asset;

class BookingRepository extends Repository
{
    public function getBookingsByAssetId($asset_id)
    {
        $asset = Asset::with([
        'bookings' => fn ($bookings) => $bookings->chaperone(),
        ])->findOrFail($asset_id);
        if ($asset->bookings->isEmpty()) {
            $refl = new \ReflectionClass($asset);
            throw new \App\Exceptions\ResourceEmptyException("Ресурс [{$refl->getName()}] не имеет бронирований");
        }
        return $asset->bookings;
    }
}

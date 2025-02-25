<?php

namespace App\Modules\Booking\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => BookingResource::collection($this->collection),
        ];
    }
}
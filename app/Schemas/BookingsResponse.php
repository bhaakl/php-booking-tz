<?php

namespace App\Schemas;


/**
 * @OA\Schema(
 *     description="Asset Bookings response",
 *     type="object",
 *     title="BookingsResponse",
 * )
 */
class BookingsResponse
{
    /**
     * @OA\Property(
     *     property="data",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Booking"))
     *
     *
     * @var array $data
     */
    public array $data;

}
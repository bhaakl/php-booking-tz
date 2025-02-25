<?php

namespace App\Schemas;


/**
 * @OA\Schema(
 *     description="Booking request",
 *     type="object",
 *     title="BookingStoreRequest",
 * )
 */
class BookingStoreRequest
{
    /**
     * @OA\Property(property="asset_id", type="integer", example="123", description="ID of the Asset")
     *
     * @var string $asset_id
     */
    public string $asset_id;

    /**
     * @OA\Property(property="user_id", type="integer", example="123", description="ID of the User")
     *
     * @var string $user_id
     */
    public string $user_id;
    
    /**
     * @OA\Property(property="start_time", type="date", example="2025-02-22T09:14:48.000000", description="start booking time")
     *
     * @var string $start_time
     */
    public string $start_time;

    /**
     * @OA\Property(property="end_time", type="date", example="2025-02-22T09:14:48.000000", description="end booking time")
     *
     * @var string $end_time
     */
    public string $end_time;
}
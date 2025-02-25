<?php

namespace App\Schemas;


/**
 * @OA\Schema(
 *     description="Asset Bookings",
 *     type="object",
 *     title="AssetBookingsRequest",
 * )
 */
class AssetBookingsRequest
{
    /**
     * @OA\Property(property="id", type="integer", example="123", description="asset id")
     *
     * @var int $id
     */
    public int $id;
}
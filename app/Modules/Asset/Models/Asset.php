<?php

namespace App\Modules\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Booking\Models\Booking;

/**
 * @OA\Schema(
 *     schema="Asset",
 *     type="object",
 *     title="Booking object",
 *     required={"id", "name", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Conference Room A"),
 *     @OA\Property(property="type", type="string", example="room"),
 *     @OA\Property(property="description", type="string", example="Большая конференц-зал на 20 мест")
 * )
 */
class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}

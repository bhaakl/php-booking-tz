<?php

namespace App\Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     title="Booking",
 *     required={"asset_id", "user_id", "start_time", "end_time"},
 *     @OA\Property(property="asset_id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="start_time", type="date", example="2025-02-22T09:14:48.000000"),
 *     @OA\Property(property="end_time", type="date", example="2025-02-22T09:14:48.000000"),
 * )
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'user_id', 'start_time', 'end_time'];

    protected $dates = ['start_time', 'end_time'];

    public function asset()
    {
        return $this->belongsTo(\App\Modules\Asset\Models\Asset::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value);
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value);
    }

    public function getDurationAttribute()
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }
}

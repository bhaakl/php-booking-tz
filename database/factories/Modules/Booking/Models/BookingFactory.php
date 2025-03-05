<?php

namespace Database\Factories\Modules\Booking\Models;;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Modules\Booking\Models\Booking;
use App\Modules\Asset\Models\Asset;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Booking\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // Генерируем время начала бронирования в ближайшее время
       $startTime = $this->faker->dateTimeBetween('now', '+1 week');
       // Время окончания бронирования через час от начала
       $endTime = (clone $startTime)->modify('+1 hour');

       return [
           'asset_id'   => Asset::factory(),
           'user_id'    => $this->faker->numberBetween(1, 100),
           'start_time' => Carbon::instance($startTime),
           'end_time'   => Carbon::instance($endTime),
       ];
    }
}

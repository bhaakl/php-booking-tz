<?php

namespace Database\Factories\Modules\Asset\Models;;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Asset\Models\Asset;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Asset\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['Hardware', 'Software']),
            'description' => $this->faker->text,
        ];
    }
}

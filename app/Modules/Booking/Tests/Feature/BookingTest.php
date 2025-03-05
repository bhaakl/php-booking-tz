<?php

namespace App\Modules\Booking\Tests\Feature;

use App\Modules\Booking\Models\Booking;
use App\Modules\Asset\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test store creates booking with existing asset id
     */
    #[Test]
    public function store_creates_booking_with_existing_asset_id()
    {
        $asset = Asset::factory()->create();

        $fuser_id = $this->faker->randomNumber(6);
        $this->postJson('/api/v1/bookings', [
        'asset_id' => $asset->id,
        'user_id' => $fuser_id,
        'start_time' => now()->addHour()->toDateTimeString(),
        'end_time' => now()->addHours(2)->toDateTimeString(),
        ])->assertSuccessful();

        $this->assertDatabaseHas('bookings', [
        'asset_id' => $asset->id,
        'user_id' => $fuser_id,
        ]);
    }

    /**
     * Test store fails when asset id non existing 
     */
    #[Test]
    public function store_fails_when_asset_id_non_existing()
    {
        $fasset_id = $this->faker->randomNumber(6);
        $fuser_id = $this->faker->randomNumber(6);
        $this->postJson('/api/v1/bookings', [
        'asset_id' => $fasset_id,
        'user_id' => $fuser_id,
        'start_time' => now()->addHour()->toDateTimeString(),
        'end_time' => now()->addHours(2)->toDateTimeString(),
        ])->assertStatus(422)
        ->assertJsonValidationErrors(['asset_id']);

        $this->assertDatabaseMissing('bookings', [
        'asset_id' => $fasset_id,
        'user_id' => $fuser_id,
        ]);
    }

    /**
     * Test returns array for asset with bookings
     */
    #[Test]
    public function returns_array_for_asset_with_bookings()
    {
        $asset = Asset::factory()->create();
        Booking::factory()->count(5)->create(['asset_id' => $asset->id]);

        $response = $this->getJson("/api/v1/resources/{$asset->id}/bookings");
        $response
        ->assertSuccessful()
        ->assertJsonCount(5, 'data');
        $this->assertDatabaseHas('bookings', [
        'asset_id' => $asset->id,
        ]);
    }

    /**
     * Test returns empty array for asset with no bookings
     */
    #[Test]
    public function returns_empty_array_for_asset_with_no_bookings()
    {
        $asset = Asset::factory()->create();  // asset non contains bookings

        $response = $this->getJson("/api/v1/resources/{$asset->id}/bookings");
        $response
        ->assertBadRequest();
        $this->assertDatabaseMissing('bookings', [
        'asset_id' => $asset->id,
        ]);
    }

  /**
   * Test returns 404 for non existent asset
   */
    #[Test]
    public function returns_404_for_non_existent_asset()
    {

        $response = $this->getJson("/api/v1/resources/{$this->faker->randomNumber(6)}/bookings");
        $response->assertStatus(404);
    }

  /**
   * Test destroy deletes booking successfully
   */
    #[Test]
    public function destroy_deletes_booking_successfully()
    {
        $booking = Booking::factory()->create();
        $response = $this->deleteJson("/api/v1/bookings/{$booking->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

  /**
   * Test destroy returns not found for non existing booking
   */
    #[Test]
    public function destroy_returns_not_found_for_non_existing_booking()
    {
      // несуществующий ID.
        $nonExistingId = 99999;
        $response = $this->deleteJson("/api/v1/bookings/{$nonExistingId}");

        $response->assertStatus(404);
        $response->assertJsonStructure([
        'data' => [
        'code',
        'message'
        ]
        ]);
    }
}

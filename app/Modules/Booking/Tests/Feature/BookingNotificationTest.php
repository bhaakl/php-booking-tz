<?php

namespace App\Modules\Booking\Tests\Feature;

use App\Models\User;
use App\Modules\Booking\Models\Booking;
use App\Modules\Asset\Models\Asset;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookingNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test booking created notification is sent on booking creation
     */
    #[Test]
    public function booking_created_notification_is_sent_on_booking_creation()
    {
        Notification::fake();

        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $data = [
            'asset_id'   => $asset->id,
            'user_id'    => $user->id,
            'start_time' => Carbon::now()->addHour()->toDateTimeString(),
            'end_time'   => Carbon::now()->addHours(2)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/v1/bookings', $data);
        $response->assertStatus(201);

        Notification::assertSentTo(
            [$user],
            BookingCreatedNotification::class,
            function ($notification, $channels) use ($data) {
                $this->assertDatabaseHas('bookings', $data);
                return $notification->getBooking()->asset == Asset::find(['id' => $data['asset_id']])->first();
            }
        );
    }

    /**
     * Test booking cancelled notification is sent on booking deletion
     */
    #[Test]
    public function booking_cancelled_notification_is_sent_on_booking_deletion()
    {
        Notification::fake();

        $user = User::factory()->create();
        $asset = Asset::factory()->create();
        $booking = Booking::factory()->create([
            'asset_id'   => $asset->id,
            'user_id'    => $user->id,
            'start_time' => Carbon::now()->addHour()->toDateTimeString(),
            'end_time'   => Carbon::now()->addHours(2)->toDateTimeString(),
        ]);

        $response = $this->deleteJson("/api/v1/bookings/{$booking->id}");
        $response->assertStatus(204);

        Notification::assertSentTo(
            [$user],
            BookingCancelledNotification::class,
            function ($notification, $channels) use ($booking) {
                return $notification->getBooking()->id === $booking->id;
            }
        );
    }
}

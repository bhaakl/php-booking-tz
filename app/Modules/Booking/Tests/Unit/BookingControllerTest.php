<?php

namespace App\Modules\Booking\Tests\Unit;

use App\Modules\Base\Resources\ErrorResource;
use App\Modules\Booking\Resources\BookingResource;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\JsonResource;
use Mockery;
use App\Modules\Booking\Models\Booking;
use App\Modules\Asset\Models\Asset;
use App\Http\Controllers\Api\V1\booking\BookingController;
use App\Modules\Booking\Requests\AssetBookingsRequest;
use App\Modules\Booking\Requests\BookingStoreRequest;
use App\Modules\Booking\Requests\DeleteBookingRequest;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_method_returns_booking_collection()
    {
        Booking::factory()->count(3)->create();

        $controller = app()->make(BookingController::class);
        ;
        $result = $controller->index();

        $this->assertInstanceOf(JsonResource::class, $result);
        $this->assertCount(3, $result->resource);
    }

    #[Test]
    public function store_method_creates_booking_and_returns_resource()
    {
        $asset = Asset::factory()->create();
        $data = [
            'asset_id' => $asset->id,
            'user_id' => 1,
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
        ];

        $httpRequest = \Illuminate\Http\Request::create('', 'POST', $data);
        $request = BookingStoreRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(BookingController::class);
        ;
        $result = $controller->store($mockRequest);

        $this->assertInstanceOf(BookingResource::class, $result);
        $this->assertDatabaseHas('bookings', [
            'asset_id' => $asset->id,
            'user_id' => 1,
        ]);
    }

    #[Test]
    public function asset_bookings_method_returns_collection_contained_bookings()
    {
        $asset = Asset::factory()->create();
        Booking::factory()->count(2)->create(['asset_id' => $asset->id]);

        $data = ['id' => $asset->id];

        $httpRequest = \Illuminate\Http\Request::create('', 'GET', $data);
        $request = AssetBookingsRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(BookingController::class);
        $result = $controller->assetBookings($mockRequest);

        $this->assertInstanceOf(JsonResource::class, $result);
        $this->assertCount(2, $result->resource);
    }
    #[Test]
    public function asset_bookings_method_returns_error_uncontained_bookings()
    {
        $asset_id = 3; // asset dont contains bookings
        $data = ['id' => $asset_id];

        $httpRequest = \Illuminate\Http\Request::create('', 'GET', $data);
        $request = AssetBookingsRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(BookingController::class);
        $result = $controller->assetBookings($mockRequest);

        $this->assertInstanceOf(ErrorResource::class, $result);
        $this->assertContains($result->getStatusCode(), [404, 400]);
    }

    #[Test]
    public function destroy_method_deletes_booking_with_valid_parameter()
    {
        $booking = Booking::factory()->create();
        $data = ['id' => $booking->id];

        $httpRequest = \Illuminate\Http\Request::create('', 'DELETE', $data);
        $request = DeleteBookingRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(BookingController::class);
        ;
        $result = $controller->destroy($mockRequest);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
    #[Test]
    public function destroy_method_deletes_booking_with_invalid_parameter()
    {
        $data = ['id' => 4444];  // non existing booking id

        $httpRequest = \Illuminate\Http\Request::create('', 'DELETE', $data);
        $request = DeleteBookingRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(BookingController::class);
        ;
        $result = $controller->destroy($mockRequest);
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertDatabaseMissing('bookings', ['id' => 4444]);
    }
}

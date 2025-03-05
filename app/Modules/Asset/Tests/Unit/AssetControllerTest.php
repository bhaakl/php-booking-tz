<?php

namespace App\Modules\Asset\Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\JsonResource;
use Faker\Factory as Faker;
use Mockery;
use App\Modules\Asset\Models\Asset;
use App\Http\Controllers\Api\V1\booking\AssetController;
use App\Modules\Asset\Requests\AssetStoreRequest;
use App\Modules\Asset\Resources\AssetResource;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_method_returns_asset_collection()
    {
        Asset::factory()->count(10)->create();

        $controller = app()->make(AssetController::class);
        $result = $controller->index();

        $this->assertInstanceOf(JsonResource::class, $result);
        $this->assertCount(10, $result->resource);
    }

    #[Test]
    public function store_method_creates_asset_and_return_resource()
    {
        $faker = Faker::create();
        $data = [
        'name' => $faker->name,
        'type' => $faker->word,
        'description' => $faker->sentence,
        ];

        $httpRequest = \Illuminate\Http\Request::create('', 'POST', $data);
        $request = AssetStoreRequest::createFrom($httpRequest);
        $mockRequest = Mockery::mock($request)->makePartial();

        $mockRequest->shouldReceive('validated')
        ->andReturn($data);

        $controller = app()->make(AssetController::class);
        $result = $controller->store($mockRequest);

        $this->assertInstanceOf(AssetResource::class, $result);
        $this->assertDatabaseHas('assets', $data);
    }

    #[Test]
    public function destroy_method_deletes_asset_with_valid_parameter()
    {
        $asset = Asset::factory()->create();
        $data = ['id' => $asset->id];

        $httpRequest = \Illuminate\Http\Request::create('', 'DELETE', $data);
        $mockRequest = Mockery::mock($httpRequest);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(AssetController::class);
        ;
        $result = $controller->destroy($mockRequest);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertDatabaseMissing('bookings', ['id' => $asset->id]);
    }
    #[Test]
    public function destroy_method_deletes_asset_with_invalid_parameter()
    {
        $data = ['id' => 4444];

        $httpRequest = \Illuminate\Http\Request::create('', 'DELETE', $data);
        $mockRequest = Mockery::mock($httpRequest);
        $mockRequest->shouldReceive('validated')->andReturn($data);

        $controller = app()->make(AssetController::class);
        ;
        $result = $controller->destroy($mockRequest);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertDatabaseMissing('bookings', ['id' => 4444]);
    }
}

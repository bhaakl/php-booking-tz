<?php

namespace App\Modules\Asset\Tests\Feature;

use App\Modules\Asset\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test index returns asset list
     */
    #[Test]
    public function index_returns_asset_list()
    {
        Asset::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/resources');

        $response->assertStatus(200)
        ->assertJsonStructure([
        'data' => [
          '*' => [
            'id',
            'name',
            'type',
            'description',
          ]
        ]
        ]);
    }

    /**
     * Test index returns empty list when no assets exist 
     */
    #[Test]
    public function index_returns_empty_list_when_no_assets_exist()
    {
        $response = $this->getJson('/api/v1/resources');
        $response->assertStatus(200)
        ->assertJson([
        'data' => []
        ]);
    }

    /**
     * Test store creates asset successfully
     */
    #[Test]
    public function store_creates_asset_successfully()
    {
        $data = [
          'name' => $this->faker->name,
          'type' => $this->faker->word,
          'description' => $this->faker->sentence,
        ];

        $response = $this->postJson('/api/v1/resources', $data);

        $response->assertStatus(201)
        ->assertJsonStructure([
        'data' => [
          'id',
          'name',
          'type',
          'description',
        ]
        ]);

        $this->assertDatabaseHas('assets', $data);
    }

    /**
     * Test store fails when required fields are missing  
     */
    #[Test]
    public function store_fails_when_required_fields_are_missing()
    {
        $response = $this->postJson('/api/v1/resources', []);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'type']);
    }
}

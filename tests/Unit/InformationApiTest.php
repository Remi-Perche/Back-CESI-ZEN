<?php

namespace Tests\Feature;

use App\Models\Information;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InformationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        return $this->actingAs($user, 'sanctum');
    }

    public function test_index_returns_all_informations()
    {
        $this->authenticate();
        Information::factory()->count(2)->create();

        $response = $this->getJson('/api/informations');

        $response->assertOk()
                 ->assertJsonCount(2);
    }

    public function test_show_returns_specific_information()
    {
        $this->authenticate();
        $information = Information::factory()->create();

        $response = $this->getJson("/api/informations/{$information->id}");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $information->id]);
    }

    public function test_store_creates_an_information()
    {
        $this->authenticate();
        $menu = Menu::factory()->create();
        $data = [
            'title' => 'Info Title',
            'description' => 'Info Desc',
            'menu_id' => $menu->id
        ];

        $response = $this->postJson('/api/informations', $data);

        $response->assertCreated()
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('informations', $data);
    }

    public function test_update_modifies_an_information()
    {
        $this->authenticate();
        $information = Information::factory()->create();

        $response = $this->putJson("/api/informations/{$information->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertOk()
                 ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_destroy_deletes_an_information()
    {
        $this->authenticate();
        $information = Information::factory()->create();

        $response = $this->deleteJson("/api/informations/{$information->id}");

        $response->assertOk()
                 ->assertJson(['code' => 200]);

        $this->assertDatabaseMissing('informations', ['id' => $information->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        return $this->actingAs($user, 'sanctum');
    }

    public function test_index_returns_all_menus()
    {
        $this->authenticate();
        Menu::factory()->count(3)->create();

        $response = $this->getJson('/api/menus');

        $response->assertOk()
                 ->assertJsonCount(3);
    }

    public function test_store_creates_a_menu()
    {
        $this->authenticate();
        $data = ['name' => 'Menu 1', 'description' => 'Desc'];

        $response = $this->postJson('/api/menus', $data);

        $response->assertCreated()
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('menus', $data);
    }

    public function test_update_modifies_a_menu()
    {
        $this->authenticate();
        $menu = Menu::factory()->create();

        $response = $this->putJson("/api/menus/{$menu->id}", ['name' => 'Updated']);

        $response->assertOk()
                 ->assertJsonFragment(['name' => 'Updated']);

        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'name' => 'Updated']);
    }

    public function test_destroy_deletes_a_menu()
    {
        $this->authenticate();
        $menu = Menu::factory()->create();

        $response = $this->deleteJson("/api/menus/{$menu->id}");

        $response->assertOk()
                 ->assertJson(['code' => 200]);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }
}

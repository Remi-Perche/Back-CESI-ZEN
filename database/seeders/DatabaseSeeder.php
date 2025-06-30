<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Information;
use App\Models\BreathingExercise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $menus = Menu::factory()->count(3)->create();

        foreach ($menus as $menu) {
            Information::factory()->count(5)->create([
                'menu_id' => $menu->id,
            ]);
        }

        BreathingExercise::create([
            'title' => 'test',
            'inspirationDuration' => 1,
            'apneaDuration' => 1,
            'expirationDuration' => 1
        ]);

        User::create([
            'firstname' => 'a',
            'lastname' => 'a',
            'email' => 'a@a.fr',
            'password' => Hash::make('azerty'),
            'role' => 'Super-Administrateur',
            'actif' => true
        ]);
    }
}

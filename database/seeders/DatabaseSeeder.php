<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Dispatcher;
use App\Models\Location;
use App\Models\Manager;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Isključi foreign key provjere
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tablice
        DB::table('dispatcher_manager')->truncate();
        Dispatcher::truncate();
        Manager::truncate();
        Location::truncate();
        Users::truncate();

        // Ponovno uključi foreign key provjere
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Kreiranje testnih korisnika sa različitim rolama
        $guest = Users::create([
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'password' => Hash::make('password'),
            'role' => 'guest',
        ]);

        $reader = Users::create([
            'name' => 'Reader User',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
            'role' => 'reader',
        ]);

        $editor = Users::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
        ]);

        $admin = Users::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('Seeded 4 users with roles: guest, reader, editor, admin.');

        // Kreiranje lokacija
        $locations = Location::factory(5)->create();
        $this->command->info('Seeded 5 locations.');

        // Kreiranje dispatchera
        $dispatchers = Dispatcher::factory(10)->create();
        $this->command->info('Seeded 10 dispatchers.');

        // Kreiranje managera i povezivanje s dispatcherima
        $managers = Manager::factory(8)->create()->each(function($manager) use ($dispatchers) {
            $assignedDispatchers = $dispatchers->random(rand(1, 3));
            $manager->dispatchers()->attach($assignedDispatchers->pluck('id'));
        });
        $this->command->info('Seeded 8 managers and assigned dispatchers.');
    }
}

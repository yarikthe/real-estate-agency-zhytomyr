<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            CreateUsersSeeder::class,
            UserTableSeeder::class,
            OwnerTableSeeder::class,
            BlogTableSeeder::class,
            ObektsTableSeeder::class,
            NoteTableSeeder::class,
        ]);
    }
}

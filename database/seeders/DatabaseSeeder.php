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
        if (env('DB_SEED_USE_SQL')) {
            $this->call([
                SqlFileSeeder::class,
            ]);
        } else {
            $this->call([
                DataFactorySeeder::class,
            ]);
        }
    }
}

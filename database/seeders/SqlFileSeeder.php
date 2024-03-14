<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SqlFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (env('APP_ENV') == 'local') {
            $path = 'database/sql/db_local.sql';
            \DB::unprepared(file_get_contents($path));
        } else {
            $path = 'database/sql/db_production.sql';
            \DB::unprepared(file_get_contents($path));
        }
    }
}

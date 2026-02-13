<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            CustomerSeeder::class,
            DemoOrderSeeder::class,
        ]);
    }
}

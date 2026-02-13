<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

/**
 * Class CustomerSeeder
 * @package Database\Seeders
 */
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Customer::factory()->count(1000)->create();
    }
}

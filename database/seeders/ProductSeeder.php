<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class ProductSeeder
 */
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::factory()->count(300)->create();

        $total = 3000000;
        $batchSize = 2000; // tune: 1000-5000 depending on your machine

        // We'll use Faker without unique() for speed
        $faker = fake();

        $now = now();

        // Optional: disable query log for memory
        DB::disableQueryLog();

        for ($offset = 0; $offset < $total; $offset += $batchSize) {
            $rows = [];

            $limit = min($batchSize, $total - $offset);

            for ($i = 0; $i < $limit; $i++) {
                $name = Str::title($faker->words(2, true));

                $rows[] = [
                    'name' => $name,
                    'sku' => 'SKU-'.strtoupper(Str::random(12)), // large space
                    'price' => $faker->numberBetween(50, 5000),
                    'stock_qty' => $faker->numberBetween(0, 500),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Bulk insert
            Product::query()->insert($rows);
        }
    }
}

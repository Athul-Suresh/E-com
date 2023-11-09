<?php

namespace Database\Seeders;

use App\Models\ProductTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('product_tags')->insert([

            [
                'name' => 'Technology',
                'status' => 1
            ],
            [
                'name' => 'OLED',
                'status' => 1
            ],
            [
                'name' => 'LED',
                'status' => 1
            ],
            [
                'name' => 'CURVED',
                'status' => 1
            ],



        ]);
            $this->command->info('Product Tag Seeding Finished!');
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'name' => 'Home',
                'url' => 'dashboard',
                'icon' => '',
                'parent_id' => null,
                'is_active' => True,
                'order' => 1
            ],

        ]);
    }
}

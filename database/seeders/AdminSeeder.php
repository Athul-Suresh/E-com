<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin           = new Admin();
            $admin->name     = "Super Admin";
            $admin->email    = "superadmin@admin.com";
            $admin->phone    = "9947163691";
            $admin->password = Hash::make('123456789');
            $admin->save();
    }
}

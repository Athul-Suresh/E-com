<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'athuldevagiri97@gmail.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "Athul Suresh";
            $user->email = "athuldevagiri97@gmail.com";
            $user->password = Hash::make('12345678');
            $user->save();
        }
    }
}

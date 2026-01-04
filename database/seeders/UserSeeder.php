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
        //"create" persists data in the db
        User::factory()->count(3)->create();
        //this works for creating data in different db, but when running php artisan migrate:fresh --seed BECOMES PROBLEMATIC 
        // $connection = 'sqlite';
        // //"make" does not persist data
        // $users = User::factory()->count(3)->make();
        // $users->each(function ($user) use ($connection) {
        //     $user->setConnection($connection);
        //     $user->save();
        // });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //relationship chaining, country calls relationship with user and provides the name of the
        //relationship in the Country Model, User calls relationship with Address model
        Country::factory()
        ->count(3) //create 3 countries
        ->has(User::factory()->count(3) //create 1 users for each country
        ->has(Comment::factory()->count(5), 'comment'), 'citizens')->create();
    }
}

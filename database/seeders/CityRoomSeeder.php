<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use function Symfony\Component\Clock\now;

class CityRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i= 1; $i <= 10; $i++){
            DB::table('city_room')->insert([
                'city_id' => mt_rand(1,3),
                'room_id' => mt_rand(1,10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addMinutes(30),
            ]);
        }
    }
}

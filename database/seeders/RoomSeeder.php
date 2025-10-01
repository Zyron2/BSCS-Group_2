<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'CEIT 10', 'type' => 'Laboratory', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'CEIT 11', 'type' => 'Lecture', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'CEIT 12', 'type' => 'Lecture', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'CEIT 13', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 14', 'type' => 'Laboratory', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'CEIT 15', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 16', 'type' => 'Laboratory', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'CEIT 17', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 18', 'type' => 'Laboratory', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 19', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 20', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 21', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 22', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 23', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 24', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 25', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 26', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 27', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 28', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 29', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'CEIT 30', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
            ['name' => 'ICT 1', 'type' => 'Lecture', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'ICT 2', 'type' => 'Lecture', 'air_conditioned' => true, 'capacity' => 50],
            ['name' => 'Open Classroom', 'type' => 'Lecture', 'air_conditioned' => false, 'capacity' => 50],
        ];

        DB::table('rooms')->insert($rooms);
    }
}

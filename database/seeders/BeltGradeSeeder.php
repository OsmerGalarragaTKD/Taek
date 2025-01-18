<?php

namespace Database\Seeders;

use App\Models\BeltGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeltGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->createKupGrades();
        $this->createPoomGrades();
        $this->createDanGrades();
    }

    private function createKupGrades()
    {
        for ($i = 1; $i <= 10; $i++) {
            BeltGrade::create([
                'type' => 'KUP',
                'level' => $i,
                'name' => "Kup",
                'color' => 'Cinturon de Color'
            ]);
        }
    }

    private function createPoomGrades()
    {
        for ($i = 1; $i <= 5; $i++) {
            BeltGrade::create([
                'type' => 'POOM',
                'level' => $i,
                'name' => "Poom",
                'color' => 'Cinturon de Bodan'
            ]);
        }
    }

    private function createDanGrades()
    {
        for ($i = 1; $i <= 10; $i++) {
            BeltGrade::create([
                'type' => 'DAN',
                'level' => $i,
                'name' => "Dan",
                'color' => 'Cinturon Negro'
            ]);
        }
    }
}

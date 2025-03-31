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
    $kup = [
        'BLANCO',  
        'PUNTA AMARILLA',  
        'AMARILLO',  
        'NARANJA',  
        'VERDE',  
        'CELESTE',  
        'AZUL',  
        'MARRÓN',  
        'ROJO',  
        'ROJO 1 PUNTA NEGRA',  
        'ROJO 2 PUNTA NEGRA'  
    ];

    $kup = array_reverse($kup);

    for ($i = 0; $i <= 10; $i++) {  
        BeltGrade::create([  
            'type' => 'KUP',  
            'level' => $i + 1,  
            'name' => "Kup",  
            'color' => $kup[$i]  
        ]);  
    }
}

    private function createPoomGrades()
    {
        for ($i = 1; $i <= 3; $i++) {
            BeltGrade::create([
                'type' => 'POOM',
                'level' => $i,
                'name' => "Poom",
                'color' => 'Cinturon de Bodan '.$i
            ]);
        }
    }

    private function createDanGrades()
    {
        for ($i = 1; $i <= 9; $i++) {
            BeltGrade::create([
                'type' => 'DAN',
                'level' => $i,
                'name' => "Dan",
                'color' => 'Cinturon Negro '.$i
            ]);
        }
    }
}

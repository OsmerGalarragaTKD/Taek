<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Infantil',
                'type' => 'Kyorugui',
                'gender' => 'M',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Kyorugui',
                'gender' => 'M',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Kyorugui',
                'gender' => 'M',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Kyorugui',
                'gender' => 'M',
                'min_age' => 18,
                'max_age' => 35,
            ],
            [
                'name' => 'Infantil',
                'type' => 'Poomsae',
                'gender' => 'M',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Poomsae',
                'gender' => 'M',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Poomsae',
                'gender' => 'M',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Poomsae',
                'gender' => 'M',
                'min_age' => 18,
                'max_age' => 35,
            ],
            [
                'name' => 'Infantil',
                'type' => 'Para-Kyorugui',
                'gender' => 'M',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Para-Kyorugui',
                'gender' => 'M',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Para-Kyorugui',
                'gender' => 'M',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Para-Kyorugui',
                'gender' => 'M',
                'min_age' => 18,
                'max_age' => 35,
            ],

            [
                'name' => 'Infantil',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 18,
                'max_age' => 35,
            ],

            [
                'name' => 'Infantil',
                'type' => 'Kyorugui',
                'gender' => 'F',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Kyorugui',
                'gender' => 'F',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Kyorugui',
                'gender' => 'F',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Kyorugui',
                'gender' => 'F',
                'min_age' => 18,
                'max_age' => 35,
            ],
            [
                'name' => 'Infantil',
                'type' => 'Poomsae',
                'gender' => 'F',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Poomsae',
                'gender' => 'F',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Poomsae',
                'gender' => 'F',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Poomsae',
                'gender' => 'F',
                'min_age' => 18,
                'max_age' => 35,
            ],
            [
                'name' => 'Infantil',
                'type' => 'Para-Kyorugui',
                'gender' => 'F',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Para-Kyorugui',
                'gender' => 'F',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Para-Kyorugui',
                'gender' => 'F',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Para-Kyorugui',
                'gender' => 'F',
                'min_age' => 18,
                'max_age' => 35,
            ],

            [
                'name' => 'Infantil',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 3,
                'max_age' => 11,
            ],
            [
                'name' => 'Cadete',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'Juvenil',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 15,
                'max_age' => 17,
            ],
            [
                'name' => 'Adulto',
                'type' => 'Para-Poomsae',
                'gender' => 'M',
                'min_age' => 18,
                'max_age' => 35,
            ],
            
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationalProgram;
use Illuminate\Support\Str;


class EducationalProgramseed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EducationalProgram::create([
            'name' => [
                'uz' => 'Magistatura',

            ],
            'slug' => Str::slug('Magistatura'), // Slug generatsiya
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2-yangi yozuv
        EducationalProgram::create([
            'name' => [
                'uz' => 'Bakalavr',

            ],
            'slug' => Str::slug('Bakalavr'), // Slug generatsiya
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

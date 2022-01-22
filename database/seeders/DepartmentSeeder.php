<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create(['department' => 'Dermatologists']);
        Department::create(['department' => 'Ophthalmologists']);
        Department::create(['department' => 'Gastroenterologists']);
        Department::create(['department' => 'Allergists']);
        Department::create(['department' => 'Infectious']);
        Department::create(['department' => 'Endocrinologists']);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DriverLicenseType;

class DriverLicenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maritalStatus = [
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B1'],
            ['id' => 3, 'name' => 'B2'],
            ['id' => 4, 'name' => 'C'],
            ['id' => 5, 'name' => 'D'],
        ];
        foreach ($maritalStatus as $ms) {
            DriverLicenseType::create($ms);
        }
    }
}

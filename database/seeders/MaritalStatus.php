<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaritalStatus as MaritalStatusModel;

class MaritalStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maritalStatus = [
            ['id' => 1, 'name' => 'Belum Menikah'],
            ['id' => 2, 'name' => 'Menikah'],
            ['id' => 3, 'name' => 'Janda'],
            ['id' => 4, 'name' => 'Duda'],
        ];
        foreach ($maritalStatus as $ms) {
            MaritalStatusModel::create($ms);
        }
    }
}

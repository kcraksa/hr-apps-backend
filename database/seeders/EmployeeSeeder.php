<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      Employee::create([
          'fullname' => 'John Doe',
          'nip' => '123456',
          'phone_number' => '081234567890',
          'office_place_id' => 1,
          'department_id' => 1,
          'section_id' => 1,
          'position_id' => 2,
          'level_id' => 1,
      ]);
    }
}

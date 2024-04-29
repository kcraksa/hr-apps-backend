<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PlaceOffice;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use App\Models\Level;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder untuk tabel place_office
        $office = PlaceOffice::create([
            'code' => 'KP',
            'name' => 'Kantor Pusat',
            'address' => 'Jl. Jendral Sudirman No. 123, Jakarta',
        ]);

        // Seeder untuk tabel departments
        $departments = [
            ['code' => 'HR', 'name' => 'Human Resources'],
            ['code' => 'IT', 'name' => 'Information Technology'],
            // Tambahkan departemen lain sesuai kebutuhan
        ];
        foreach ($departments as $department) {
            Department::create($department);
        }

        // Seeder untuk tabel sections
        $sections = [
            ['department_id' => 1, 'code' => 'HR-001', 'name' => 'Recruitment'],
            ['department_id' => 1, 'code' => 'HR-002', 'name' => 'Training'],
            ['department_id' => 2, 'code' => 'IT-001', 'name' => 'Development'],
            // Tambahkan bagian lain sesuai kebutuhan
        ];
        foreach ($sections as $section) {
            Section::create($section);
        }

        // Seeder untuk tabel levels
        $levels = [
            ['name' => '3A'],
            ['name' => '4'],
            ['name' => '3B'],
            // Tambahkan level lain sesuai kebutuhan
        ];
        foreach ($levels as $level) {
            Level::create($level);
        }

        // Seeder untuk tabel positions
        $positions = [
            ['department_id' => 1, 'section_id' => 1, 'level_id' => 1, 'code' => 'HR-REC-001', 'name' => 'Recruitment Officer'],
            ['department_id' => 1, 'section_id' => 2, 'level_id' => 2, 'code' => 'HR-TRN-001', 'name' => 'Training Manager'],
            ['department_id' => 2, 'section_id' => 3, 'level_id' => 3, 'code' => 'IT-DEV-001', 'name' => 'Software Developer'],
            // Tambahkan posisi lain sesuai kebutuhan
        ];
        foreach ($positions as $position) {
            Position::create($position);
        }

        Employee::create([
            'fullname' => 'John Doe',
            'nip' => '123456',
            'phone_number' => '081234567890',
            'office_place_id' => 1,
            'department_id' => 1,
            'section_id' => 1,
            'position_id' => 1,
            'level_id' => 1,
        ]);
    }
}

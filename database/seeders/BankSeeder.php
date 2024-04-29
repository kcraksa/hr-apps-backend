<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['id' => 1, 'kode' => 'BCA', 'name' => 'Bank Central Asia'],
            ['id' => 2, 'kode' => 'Mandiri', 'name' => 'Bank Mandiri'],
            ['id' => 3, 'kode' => 'BRI', 'name' => 'Bank Rakyat Indonesia'],
            ['id' => 4, 'kode' => 'BNI', 'name' => 'Bank Negara Indonesia'],
        ];
        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}

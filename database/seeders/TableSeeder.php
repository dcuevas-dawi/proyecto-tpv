<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // Seeder for the tables, it runs always when a new user (stablishment) is created
    public function run()
    {
        $user = User::latest()->first(); // Last created user id

        // Creates 10 tables for new user (stablishment)
        if ($user) {
            foreach (range(1, 10) as $index) {
                Table::create([
                    'user_id' => $user->id,
                    'number' => $index,
                    'status' => false,
                ]);
            }
        }
    }
}


<?php

namespace Database\Seeders;

use App\Models\member;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Schema::disableForeignKeyConstraints();
        Task::truncate();
        member::truncate();
        User::truncate();

        // User::factory(5)->create();
        Task::factory(50)->create();
        member::factory(12)->create();
        Schema::enableForeignKeyConstraints();
    }
}

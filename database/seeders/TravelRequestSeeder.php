<?php

namespace Database\Seeders;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::role('user')->get();

        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário com papel "user" encontrado.');
            return;
        }

        TravelRequest::factory(15)->make()->each(function ($request) use ($users) {
            $request->user_id = $users->random()->id;
            $request->save();
        });
    }
}

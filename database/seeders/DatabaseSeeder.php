<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Item;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Julius',
            'email' => 'julius_melgar@spice-factory.ph',
        ]);

        $checklists = Checklist::factory()
            ->count(14)
            ->for($user)
            ->create();

        foreach ($checklists as $checklist) {
            Item::factory()
                ->count(rand(1, 6))
                ->for($checklist)
                ->for($user)
                ->hasSubItems(rand(1, 3), [
                    'checklist_id' => $checklist->id,
                    'user_id' => $user->id,
                    'to_complete_by_date' => null,
                ])
                ->create();
        }
    }
}

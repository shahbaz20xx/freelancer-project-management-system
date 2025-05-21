<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Application;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VoyagerDatabaseSeeder::class);

        User::factory()->admin()->create([
            'name' => 'Shahbaz',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
        ]);

        User::factory()->regularUser()->count(20)->create();

        Project::factory()->count(30)->create();

        Task::factory()->count(50)->create();
        
        Application::factory()->count(150)->create();

        Invoice::factory()->count(100)->create();

        $projects = Project::all();
        foreach ($projects as $project) {
            $acceptedApplication = $project->applications()->where('status', 'accepted')->first();

            if ($acceptedApplication) {
                $project->applications()
                    ->where('id', '!=', $acceptedApplication->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            }
        }

        $this->command->info('Database seeded successfully!');
    }
}

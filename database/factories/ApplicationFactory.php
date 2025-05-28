<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $project = Project::whereIn('status', ['in_progress', 'completed'])
                          ->inRandomOrder()
                          ->first();
        
        if (!$project) {
            $project = Project::inRandomOrder()->first();
        }
        
        if (!$project) {
            $project = Project::factory()->create();
        }
        $projectId = $project->id;

        $talent = User::inRandomOrder()->first();
        if (!$talent) {
            $talent = User::factory()->create();
        }
        $talentId = $talent->id;

        $status = 'pending';

        if ($project->talent_id !== null) {
            if ($project->talent_id === $talentId) {
                $status = 'accepted';
            } else {
                $status = 'rejected';
            }
        }

        return [
            'project_id' => $projectId,
            'talent_id' => $talentId,
            'cover_letter' => $this->faker->boolean(70) ? $this->faker->paragraph(rand(1, 3)) : null,
            'status' => $status,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $project = Project::where('billing_type', 'task')->inRandomOrder()->first();

        // Fallback: If no project with billing_type 'task' exists, create one.
        if (!$project) {
            $project = Project::factory()->create(['billing_type' => 'task']);
        }

        $projectId = $project->id;

        return [
            'project_id' => $projectId, // Assign an ID from an existing project with billing_type 'task'
            'title' => $this->faker->sentence(rand(2, 5)),
            'description' => $this->faker->paragraph(rand(2, 5)),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'price' => $this->faker->randomFloat(2, 20, 500), // Random price between 20 and 500
        ];
    }
}

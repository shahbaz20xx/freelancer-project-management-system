<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get a random existing recruiter. Ensure users are seeded first.
        $recruiterId = User::inRandomOrder()->first()->id;

        $projectId = null;
        $taskId = null;
        $invoiceStatus = 'pending'; // Default invoice status
        $issuedAt = $this->faker->dateTimeBetween('-6 months', 'now');
        $dueAt = (clone $issuedAt)->modify('+1 month'); // Default due date

        // Determine if it's project-based or task-based
        // We need to ensure uniqueness for project_id OR task_id
        $isProjectBased = $this->faker->boolean(50); // 50% chance for project-based

        if ($isProjectBased) {
            // Always create a new project for this invoice to guarantee uniqueness
            // and ensure it meets the status criteria.
            $project = Project::factory()->create([
                'status' => $this->faker->randomElement(['in_progress', 'completed']),
                'talent_id' => User::inRandomOrder()->first()->id // Ensure talent exists for the new project
            ]);
            $projectId = $project->id;
            $taskId = null; // Ensure task_id is null for project-based invoices

            // Determine invoice status based on project status
            if ($project->status === 'completed') {
                $invoiceStatus = $this->faker->randomElement(['paid', 'overdue']);
            } else { // in_progress
                $invoiceStatus = 'pending';
            }

        } else { // Task-based invoice
            // Always create a new task for this invoice to guarantee uniqueness.
            // This new task factory will also create its associated project.
            $task = Task::factory()->create();
            $taskId = $task->id;
            $projectId = null; // Ensure project_id is null for task-based invoices

            // Determine invoice status based on task status
            if ($task->status === 'completed') {
                $invoiceStatus = $this->faker->randomElement(['paid', 'overdue']);
            } else { // pending or in_progress
                $invoiceStatus = 'pending';
            }
        }

        // Adjust issued_at and due_at based on invoice status
        if ($invoiceStatus === 'paid') {
            $issuedAt = $this->faker->dateTimeBetween('-1 year', '-2 months');
            $dueAt = (clone $issuedAt)->modify('+1 month');
        } elseif ($invoiceStatus === 'overdue') {
            $issuedAt = $this->faker->dateTimeBetween('-1 year', '-2 months');
            $dueAt = $this->faker->dateTimeBetween('-1 month', 'yesterday');
        } else { // pending
            $issuedAt = $this->faker->dateTimeBetween('-1 month', 'now');
            $dueAt = $this->faker->dateTimeBetween('tomorrow', '+2 months');
        }

        return [
            'recruiter_id' => $recruiterId,
            'project_id' => $projectId, // Will be null if task_id is set
            'task_id' => $taskId,       // Will be null if project_id is set
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'status' => $invoiceStatus,
            'issued_at' => $issuedAt,
            'due_at' => $dueAt,
        ];
    }
}


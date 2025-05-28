<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Project::class;

    public function definition()
    {
        $recruiterId = User::inRandomOrder()->first()->id;

        $talentId = null;
        if ($this->faker->boolean(70)) {
            $talentUser = User::where('id', '!=', $recruiterId)->inRandomOrder()->first();

            if ($talentUser) {
                $talentId = $talentUser->id;
            }
        }

        $possibleStatuses = ['open', 'cancelled'];

        if ($talentId !== null) {
            $possibleStatuses = ['in_progress', 'completed'];
        }

        $categoryId = ProjectCategory::inRandomOrder()->first()->id;
        $typeId = ProjectType::inRandomOrder()->first()->id;

        return [
            'title' => $this->faker->sentence(rand(3, 7)),
            'description' => $this->faker->paragraph(rand(3, 8)),
            'recruiter_id' => $recruiterId,
            'talent_id' => $talentId,
            'project_category_id' => $categoryId,
            'project_type_id' => $typeId,
            'status' => $this->faker->randomElement($possibleStatuses),
            'budget' => $this->faker->randomFloat(2, 100, 5000),
            'billing_type' => $this->faker->randomElement(['project', 'task']),
            'responsibility' => $this->faker->text(300), // Generates a text of about 300 characters
            'qualifications' => $this->faker->text(300), // Generates a text of about 300 characters
            'experience' => $this->faker->randomElement([
                '1 years',
                '2 years',
                '3 years',
                '4 years',
                '5 years',
                '6 years',
                '7 years',
                '8 years',
                '9 years',
                '10 years',
                '10+ years',
            ]),
        ];
    }
}

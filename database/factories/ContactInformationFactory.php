<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\ContactInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactInformation>
 */
class ContactInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'excerpt' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'status' => PublicationStatus::Draft->value,
        ];
    }
}

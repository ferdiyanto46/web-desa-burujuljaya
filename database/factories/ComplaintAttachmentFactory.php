<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComplaintAttachment>
 */
final class ComplaintAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<ComplaintAttachment>
     */
    protected $model = ComplaintAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'complaint_id' => Complaint::factory(),
            'file_path' => 'complaints/'.fake()->uuid().'.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(100000, 5000000), // 100KB to 5MB
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Agenda>
 */
final class AgendaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Agenda::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('now', '+2 months'));
        $end = (clone $start)->addHours($this->faker->numberBetween(1, 8));

        return [
            // Lazy load author via closure to avoid N+1 spawn if overridden
            'user_id' => fn () => User::factory()->create()->id,
            'title' => 'Agenda '.$this->faker->words(3, true),
            'content' => '<p>'.implode('</p><p>', $this->faker->paragraphs(2)).'</p>',
            'location' => $this->faker->address(),
            'start_datetime' => $start,
            'end_datetime' => $end,
            'status' => ArticleStatus::Draft,
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the agenda is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the agenda is an upcoming event (published and in the future).
     */
    public function upcoming(): static
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('now', '+1 month'));
        $end = (clone $start)->addHours(4);

        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'start_datetime' => $start,
            'end_datetime' => $end,
            'published_at' => now()->subDays(2),
        ]);
    }

    /**
     * Indicate that the agenda is a past event.
     */
    public function past(): static
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('-2 months', '-1 week'));
        $end = (clone $start)->addHours(4);

        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'start_datetime' => $start,
            'end_datetime' => $end,
            'published_at' => (clone $start)->subWeeks(2),
        ]);
    }
}

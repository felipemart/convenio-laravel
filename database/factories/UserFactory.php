<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $empresa = Empresa::first();

        if (empty($empresa)) {
            $empresa = Empresa::factory()->create();
        }

        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'empresa_id'        => $empresa->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function withPermissions(string $permissions): static
    {
        return $this->afterCreating(function (User $user) use ($permissions): void {
            $user->givePermission($permissions);
        });
    }

    public function withRoles(string $roles): static
    {
        return $this->afterCreating(function (User $user) use ($roles): void {
            $user->giveRole($roles);
        });
    }

    public function deleted(): static
    {
        return $this->state(fn (array $attributes): array => [
            'deleted_at' => now(),
        ]);
    }

    public function admin(): static
    {
        return $this->afterCreating(fn (User $user) => $user->giveRole('admin'));
    }
}

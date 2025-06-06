<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\App\Models\User;

class UserFactory extends Factory
{
    protected static ?string $password;
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['Mr', 'Mrs', 'Miss', 'Dr', 'Prof']),
            'first_name' => $this->faker->firstName(),
            'other_name' => $this->faker->lastName(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'nic' => Str::random(10),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'designation' => $this->faker->jobTitle(),
            'default_role_id' => null,
            'telephone' => $this->faker->phoneNumber(),
            'last_seen_at' => null,
            'online_status' => 'LOGGED_OUT',
            'is_locked' => false,
            'signature_path' => null,
            'is_active' => true,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_locked' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

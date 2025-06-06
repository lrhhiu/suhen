<?php

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // Corrected use statement for Password rule
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Modules\User\App\Models\User; // Corrected User model namespace

new #[Layout('components.layouts.auth')] class extends Component {
    public string $first_name = '';
    public string $other_name = '';
    public string $username = '';
    public string $email = ''; // Email is optional but can be provided
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Define validation rules.
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'other_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:' . User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email'], // Corrected unique rule for email
            'password' => ['required', 'string', Password::defaults(), 'confirmed'], // Using Password::defaults()
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate(); // Uses rules() method

        $validated['password'] = Hash::make($validated['password']);

        // Handle empty email string to store as null
        if (empty($validated['email'])) {
            $validated['email'] = null;
        }

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- First Name -->
        <flux:input
            wire:model="first_name"
            :label="__('First Name')"
            type="text"
            required
            autofocus
            autocomplete="given-name"
            :placeholder="__('First name')"
        />

        <!-- Other Name -->
        <flux:input
            wire:model="other_name"
            :label="__('Other Name')"
            type="text"
            required
            autocomplete="family-name"
            :placeholder="__('Other name (e.g., Last Name)')"
        />

        <!-- Username -->
        <flux:input
            wire:model="username"
            :label="__('Username')"
            type="text"
            required
            autocomplete="username"
            :placeholder="__('Username')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email Address (Optional)')"
            type="email"
            autocomplete="email"
            :placeholder="__('email@example.com')"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>

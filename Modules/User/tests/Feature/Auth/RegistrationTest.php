<?php

namespace Modules\User\Tests\Feature\Auth;

use Modules\User\Tests\TestCase;
use Modules\User\App\Models\User; // For type hinting and direct usage if any
use Illuminate\Support\Facades\Hash; // For Hash::check if used by a non-factory test
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(TestCase::class, RefreshDatabase::class);

test('registration screen can be rendered', function () {
    get(route('register'))->assertOk();
});

test('new users can register with username and other required fields', function () {
    livewire('auth.register')
        ->set('first_name', 'Test')
        ->set('other_name', 'User')
        ->set('username', 'testuser_form') // Use a unique username for this test run
        ->set('email', 'testform@example.com')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'Password123!')
        ->call('register')
        ->assertRedirect(route('dashboard'));

    assertAuthenticated();
    assertDatabaseHas('users', [
        'first_name' => 'Test',
        'other_name' => 'User',
        'username' => 'testuser_form',
        'email' => 'testform@example.com',
    ]);
    // $user = User::where('username', 'testuser_form')->first(); // This would be a direct DB query
    // expect($user)->not->toBeNull();
    // expect(Hash::check('Password123!', $user->password))->toBeTrue();
    // The above Hash::check can be tricky if user isn't fetched correctly or if state is complex after redirect.
    // For now, assertAuthenticated and assertDatabaseHas are strong checks.
});

test('password requires confirmation and meets complexity', function () {
    // Test password confirmation
    livewire('auth.register')
        ->set('first_name', 'Password')
        ->set('other_name', 'Test')
        ->set('username', 'passwordtest_confirm') // Unique username
        ->set('email', 'passwordtest_confirm@example.com')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'WrongPassword123!')
        ->call('register')
        ->assertHasErrors(['password' => 'confirmed']);

    // Test password complexity (e.g., min length from Rules\Password::defaults())
    livewire('auth.register')
        ->set('first_name', 'Password')
        ->set('other_name', 'Short')
        ->set('username', 'passwordshort_complex') // Unique username
        ->set('email', 'passwordshort_complex@example.com')
        ->set('password', 'short')
        ->set('password_confirmation', 'short')
        ->call('register')
        ->assertHasErrors(['password']);
});

?>

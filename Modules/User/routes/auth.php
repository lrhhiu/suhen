<?php

use Modules\User\App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login') // Changed: Removed 'user::livewire.' prefix
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'user::livewire.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'user::livewire.auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    // Assuming other Volt routes might need similar changes if this works
    Volt::route('verify-email', 'user::livewire.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'user::livewire.auth.confirm-password')
        ->name('password.confirm');
});

Route::post('logout', Modules\User\App\Livewire\Actions\Logout::class)
    ->name('logout');

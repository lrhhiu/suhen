<?php

namespace Modules\User\Tests\Feature\Auth;

use Modules\User\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
// No User::factory() or other imports needed for just this one test

uses(TestCase::class, RefreshDatabase::class); // RefreshDatabase might be overkill but harmless

test('login screen can be rendered', function () {
    get(route('login'))->assertOk();
});

?>

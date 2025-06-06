<?php

namespace Modules\User\Tests;

// Ensure this path is correct for your project structure
// It should point to the main application's TestCase.
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Additional module-specific setup can go here if needed.
        // For now, relying on nwidart/laravel-modules auto-discovery.
    }
}

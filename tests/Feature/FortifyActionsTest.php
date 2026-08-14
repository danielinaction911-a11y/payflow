<?php

namespace Tests\Feature;

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use PHPUnit\Framework\TestCase;

class FortifyActionsTest extends TestCase
{
    public function test_registers_fortify_two_factor_action_classes(): void
    {
        $this->assertTrue(class_exists(EnableTwoFactorAuthentication::class));
        $this->assertTrue(class_exists(ConfirmTwoFactorAuthentication::class));
        $this->assertTrue(class_exists(DisableTwoFactorAuthentication::class));
    }
}

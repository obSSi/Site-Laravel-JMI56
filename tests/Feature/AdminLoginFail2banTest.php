<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminLoginFail2banTest extends TestCase
{
    public function test_admin_login_is_temporarily_blocked_after_too_many_failed_attempts(): void
    {
        config()->set('security.admin_login.max_attempts', 3);
        config()->set('security.admin_login.lockout_seconds', 120);

        for ($i = 0; $i < 3; $i++) {
            $response = $this->from(route('login'))->post(route('login.submit'), [
                'username' => 'admin',
                'password' => 'wrong-password',
            ]);

            $response->assertRedirect(route('login'));
            $response->assertSessionHasErrors('login');
        }

        $blockedResponse = $this->from(route('login'))->post(route('login.submit'), [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $blockedResponse->assertRedirect(route('login'));
        $blockedResponse->assertSessionHasErrors('login');
        $blockedResponse->assertSessionMissing('is_admin');

        // Simulate user leaving login page and coming back.
        $this->get(route('home'))->assertOk();
        $this->get(route('login'))->assertOk();

        $stillBlockedAfterNavigation = $this->from(route('login'))->post(route('login.submit'), [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $stillBlockedAfterNavigation->assertRedirect(route('login'));
        $stillBlockedAfterNavigation->assertSessionHasErrors('login');
        $stillBlockedAfterNavigation->assertSessionMissing('is_admin');

        $this->travel(121)->seconds();

        $successResponse = $this->post(route('login.submit'), [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $successResponse->assertRedirect(route('home'));
        $successResponse->assertSessionHas('is_admin', true);
    }
}

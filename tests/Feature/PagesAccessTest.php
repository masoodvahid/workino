<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PagesAccessTest extends TestCase
{
    /**
     * Test that the coworks page is accessible.
     */
    public function test_coworks_page_is_accessible(): void
    {
        $response = $this->get(route('coworks.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that the about page is accessible.
     */
    public function test_about_page_is_accessible(): void
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
    }

    /**
     * Test that the contact page is accessible.
     */
    public function test_contact_page_is_accessible(): void
    {
        $response = $this->get(route('contact'));
        $response->assertStatus(200);
    }

    /**
     * Test that the profile page is accessible.
     */
    public function test_profile_page_is_accessible(): void
    {
        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that the login page is accessible.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('auth.login'));
        $response->assertStatus(200);
    }

    /**
     * Test that the support page is accessible.
     */
    public function test_support_page_is_accessible(): void
    {
        $response = $this->get(route('support.index'));
        $response->assertStatus(200);
    }
}

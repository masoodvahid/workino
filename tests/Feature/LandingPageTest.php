<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * Test if the landing page loads correctly and contains key sections.
     */
    public function test_landing_page_loads_and_contains_key_elements(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        
        // Check for Header elements
        $response->assertSee('ورکینو');
        $response->assertSee('خانه');
        
        // Check for Hero Section text
        $response->assertSee('بهترین فضای کار اشتراکی را');
        
        // Check for Search Box
        $response->assertSee('جستجو');
        $response->assertSee('امکانات');
        
        // Check for Latest Cowork Spaces Section
        $response->assertSee('جدیدترین فضاهای کار');
        
        // Check for specific cowork names (Mock Data)
        $response->assertSee('فضای کار اشتراکی آبی');
        $response->assertSee('استارتاپ هاب مرکزی');
        
        // Check for Footer elements
        $response->assertSee('تماس با ما');
        $response->assertSee('info@workino.com');
    }
}

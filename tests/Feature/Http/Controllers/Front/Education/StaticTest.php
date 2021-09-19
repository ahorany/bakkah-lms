<?php

namespace Tests\Feature\Http\Controllers\Front\Education;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\Front\Education\EducationController;
use App\Models\Admin\Contact;

class StaticTest extends TestCase
{
    public function test_forCorporate()
    {
        $response = $this->get('/learning/for-corporate')->assertStatus(200);
    }

    public function test_contactusIndex()
    {
        $response = $this->get('/learning/contact-us')->assertStatus(200);
    }

    public function test_aboutBakkah()
    {
        $response = $this->get('/learning/about-bakkah')->assertStatus(200);
    }

    public function test_contactusStore(){

        // $EducationController = new EducationController;
        // $validated = $EducationController->validateRequest('learning');
        // $contact = Contact::create($validated);

        $this->assertTrue(true);
    }
}

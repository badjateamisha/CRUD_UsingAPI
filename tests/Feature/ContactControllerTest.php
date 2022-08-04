<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ContactControllerTest extends TestCase
{
    
    public function test_register()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/register', [
            "name" => "amisha",            
            "email" => "amishabadjate175@gmail.com",
            "password" => "amisha123", 
            "confirmpassword" => "amisha123"         
        ]);
        $response->assertStatus(200);
    }

    public function test_Create()
    {
        $response = $this->withHeaders(['Content-Type' => 'Application/json',])
         ->json('POST', '/api/create', [
            "name" => 'komal',
            "email" => 'komal@gmail.com',
            "password" => 'komal123',
            "address" => 'abad',
            "country" => 'India',
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Contact created successfully']);
    }
    
}

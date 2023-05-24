<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterCompaniesTest extends TestCase
{
    function test_can_not_accept_invalid_mail_input()
    {
        $inputs = [
            "start_date" => "2023-05-02T02:15:00.000Z",
            "end_date" => "2023-05-04T02:15:00.000Z",
            "email" => "test",
            "symbol" => "AAB"
        ];

        $response = $this->json('POST','/api/companies',$inputs);
        $response->assertStatus(422);
        $response->assertJson(['status'=>"Invalid inputs!"]);
    }

    function test_can_not_accept_invalid_start_date()
    {
        $inputs = [
            "start_date" => "2023-05-04T02:15:00.000Z",
            "end_date" => "2023-05-02T02:15:00.000Z",
            "email" => "test@test.com",
            "symbol" => "AAB"
        ];

        $response = $this->json('POST','/api/companies',$inputs);
        $response->assertStatus(422);
        $response->assertJson(['status'=>"Invalid inputs!"]);
    }
    function test_valid_params()
    {
        $inputs = [
            "start_date" => "2023-05-02T02:15:00.000Z",
            "end_date" => "2023-05-04T02:15:00.000Z",
            "email" => "test@test.com",
            "symbol" => "AAB"
        ];

        $response = $this->json('POST','/api/companies',$inputs);
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;
use DateTime;

class RegisterTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    public function tearDown(): void
    {
        DB::rollback();
        parent::tearDown();
    }

    /**
     * Test Register api
     *
     * @return void
     */
    public function testRegisterApi()
    {
        //Need to create test personal access token for authentication
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );

        //add test access token to database.
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        //Create test data with faker.
        $faker = $this->faker;
        $password = $faker->password;
        $request = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $password,
            'c_password' => $password,
        ];
        $response = $this->post('api/register', $request);
        $response->assertStatus(200);
    }
}

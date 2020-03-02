<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;
use DateTime;

class BlogTest extends TestCase
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
     * Test List of blogs.
     *
     * @return void
     */
    public function testBlogList()
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

        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('Personal Access Token')->accessToken;

        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->get('api/blogs', $headers);
        $response->assertStatus(200);
    }

    /**
     * Test add blogs.
     *
     * @return void
     */
    public function testStoreBlog()
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

        $faker = $this->faker;
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('Personal Access Token')->accessToken;

        $headers = ['Authorization' => "Bearer $token"];

        //Create test data with faker.
        $request = [
            'name' => $faker->name,
            'description' => $faker->paragraph
        ];
        $response = $this->post('api/storeBlog', $request, $headers);
        $response->assertStatus(200);
    }

    /**
     * Test Update blogs.
     *
     * @return void
     */
    public function testUpdateBlog()
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
        $faker = $this->faker;
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('Personal Access Token')->accessToken;

        $headers = ['Authorization' => "Bearer $token"];

        //Create test data with faker.
        $request = [
            'name' => $faker->name,
            'description' => $faker->paragraph,
            'id' => $faker->randomNumber()
        ];

        //Add test data to blogs table.
        DB::table('blogs')->insert($request);
        $response = $this->put("api/updateBlog/" . $request['id'], $request, $headers);
        $response->assertStatus(200);
    }

    /**
     * Test display the specified blog.
     *
     * @return void
     */
    public function testBlogShow()
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
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('Personal Access Token')->accessToken;

        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->get('api/showBlog/1', $headers);
        $response->assertStatus(200);
    }

    /**
     * Test remove the specified blog from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function testDestroyBlog()
    {
        //Need to create test personal access token for authentication
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );

        //add test access token to database.
        DB::table('oauth_personal_access_clients')->insert(['client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,]);
        $faker = $this->faker;
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('Personal Access Token')->accessToken;

        $headers = ['Authorization' => "Bearer $token"];

        //Create test data with faker.
        $request = ['name' => $faker->name,
            'description' => $faker->paragraph,
            'id' => $faker->randomNumber()
        ];

        //Add test data to blogs table.
        DB::table('blogs')->insert($request);
        $response = $this->delete("api/deleteBlog/" . $request['id'],$request, $headers);
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Console\Commands\InstallDemoData;
use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * php artisan test --filter AuthorAdminControllerTest
 */
class AuthorAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $demoInstaller = new InstallDemoData();
        $demoInstaller->createRolesAndPermissions();
        $demoInstaller->createAdminUser();

        $this->actingAs( User::where('email', 'test@test.ts')->first() );
    }


    public function test_index_displays_authors()
    {
        Author::factory()->count(10)->create();

        $response = $this->get(route('author.index'));

        $response->assertStatus(200);
        $response->assertSee(__('Author list'));
    }

    public function test_create_requires_permission()
    {
        $response = $this->get(route('author.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_author()
    {
        $data = Author::factory()->make()->toArray();

        $response = $this->post(route('author.store'), $data);

        $this->assertDatabaseHas('authors', $data);
        $response->assertRedirect(route('author.index'));
    }

    public function test_edit_displays_author()
    {
        $author = Author::factory()->create();

        $response = $this->get(route('author.edit', $author));

        $response->assertStatus(200);
        $response->assertSee('Edit Author');
    }

    public function test_update_modifies_author()
    {
        $author = Author::factory()->create();
        $newData = ['first_name' => 'UpdatedName', 'last_name' => 'LastName'];

        $response = $this->put(route('author.update', $author), $newData);

        $this->assertDatabaseHas('authors', $newData);
        $response->assertRedirect(route('author.index'));
    }

    public function test_destroy_deletes_author()
    {
        $author = Author::factory()->create();

        $response = $this->delete(route('author.destroy', $author));

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
        $response->assertRedirect(route('author.index'));
    }

}

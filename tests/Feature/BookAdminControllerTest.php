<?php
namespace Tests\Feature;

use App\Console\Commands\InstallDemoData;
use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * php artisan test --filter BookAdminControllerTest
 */
class BookAdminControllerTest extends TestCase
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

    public function test_user_can_view_books_list()
    {

        Book::factory()->count(3)->withAuthors(2)->create();

        $response = $this->get(route('book.index'));

        $response->assertStatus(200);
        $response->assertSee('Book list');
    }

    public function test_user_can_create_a_book()
    {
        $author = Author::factory()->create();
        $data = Book::factory()->make()->toArray();
        $data['authors'] = [$author->id];

        $response = $this->post(route('book.store'), $data);

        $response->assertRedirect(route('book.index'));
        unset($data['authors']);
        $this->assertDatabaseHas('books', $data);
    }

    public function test_user_can_edit_a_book()
    {
        $book = Book::factory()->withAuthors(1)->create();

        $response = $this->get(route('book.edit', $book->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Book');
    }

    public function test_user_can_update_a_book()
    {
        $book = Book::factory()->withAuthors(1)->create();

        $data = [
            'title' => 'Updated Title',
            'price' => 150,
            'authors' => $book->authors->pluck('id')->toArray(),
        ];

        $response = $this->post(route('book.update_post', $book->id), $data);

        $response->assertRedirect(route('book.index'));
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    public function test_user_can_delete_a_book()
    {
        $book = Book::factory()->withAuthors(1)->create();

        $response = $this->delete(route('book.destroy', $book->id));

        $response->assertRedirect(route('book.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

}

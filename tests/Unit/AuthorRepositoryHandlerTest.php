<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Services\Author\AuthorRepositoryHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/*
 * php artisan test --filter AuthorRepositoryHandlerTest
 */
class AuthorRepositoryHandlerTest extends TestCase
{
    use RefreshDatabase;

    protected AuthorRepositoryHandler $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AuthorRepositoryHandler();
    }

    public function test_it_can_create_an_author()
    {
        $author = $this->repository->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertDatabaseHas('authors', [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
        $this->assertInstanceOf(Author::class, $author);
    }

    public function test_it_can_find_author_by_first_and_last_name()
    {
        Author::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe'
        ]);

        $author = $this->repository->findByFirstLastName('Jane', 'Doe');

        $this->assertNotNull($author);
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('Jane', $author->first_name);
        $this->assertEquals('Doe', $author->last_name);
    }

    public function test_it_can_update_an_author()
    {
        $author = Author::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name'
        ]);

        $updated = $this->repository->update($author, [
            'first_name' => 'New'
        ]);

        $this->assertEquals('New', $updated->first_name);
        $this->assertDatabaseHas('authors', ['first_name' => 'New']);
    }

    public function test_it_can_delete_an_author()
    {
        $author = Author::factory()->create();

        $this->repository->delete($author);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_it_can_find_by_filters_first_name()
    {
        Author::factory()->count(15)->create();
        Author::factory()->create(['first_name' => 'Unique', 'last_name' => 'Author']);

        $filters = ['first_name' => 'Unique'];
        $result = $this->repository->getFilteredPaginateItems($filters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(1, $result->items());
        $this->assertEquals('Unique', $result->items()[0]->first_name);
    }
}

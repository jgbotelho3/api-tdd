<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Book;

class BooksControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_books_endpoint()
    {
        $books = Book::factory(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use($books){

            $book = $books->first();

            $json->whereAll([
                '0.id' => $book->id,
                '0.title' => $book->title,
                '0.isbn' => $book->isbn
            ]);

            $json->hasAll(['0.id', '0.title', '0.isbn']);

            $json->whereAllType([
                '0.id' => 'integer',
                '0.title' => 'string',
                '0.isbn' => 'string'
            ]);
        });
    }

        /**
     * Test get to single book.
     *
     * @return void
     */
    public function test_get_single_book_endpoint()
    {
        $book = Book::factory(1)->createOne();

        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use($book){


            $json->whereAll([
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn
            ]);

            $json->hasAll(['id', 'title', 'isbn'])->etc();

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);
        });
    }


        /**
     * Test post to single book.
     *
     * @return void
     */
    public function test_post_books_endpoint()
    {
        $book = Book::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/books', $book);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use($book){

            $json->hasAll(['id', 'title', 'isbn'])->etc();

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn']
            ])->etc();
        });
    }

    public function test_put_books_endpoint()
    {

        $newBook = Book::factory(1)->createOne();

        $book = [
            'title' => 'Atualizando o livro',
            'isbn' => '12345646456456'
        ];

        $response = $this->putJson('/api/books/' . $newBook->id, $book);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use($book){
            $json->hasAll(['id', 'title', 'isbn'])->etc();

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn']
            ])->etc();
        });

    }

    public function test_patch_books_endpoint()
    {

        $newBook = Book::factory(1)->createOne();

        $book = [
            'title' => 'Atualizando o livro',
        ];

        $response = $this->patchJson('/api/books/' . $newBook->id, $book);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use($book){
            $json->hasAll(['id', 'title', 'isbn'])->etc();

            $json->where('title', $book['title']);
        });
    }

    public function test_delete_books_endpoint()
    {
        $book = Book::factory(1)->createOne();

        $response = $this->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(204);
    }
}

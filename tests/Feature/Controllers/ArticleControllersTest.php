<?php

namespace Test\Feature\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Article;

Class ArticleControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldReturnArticlesOnCorrectDataFormat() {
        Article::factory()->count(2)->create();

        $response = $this->getJson('api/article');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function shouldCreateArticleSuccessfully () {
        $payload = [
            'title' => 'test title',
            'description' => 'any description goes here'
        ];

        $response = $this->postJson('api/article', $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'title' => 'test title',
                'description' => 'any description goes here'
            ]
        ]);
    }

    /**
     * @test
    */
    public function shouldReturnJsonWithValidationErrorMessageOnArticleStore () {
        $payload = [
            'title' => 'a',
            'description' => 'ab'
        ];

        $response = $this->postJson('api/article', $payload);
        $response->assertStatus(422);
        $response->assertJson([
                "message" => "The given data was invalid."
        ]);
    }

    /**
     * @test
     */
    public function shouldReturnArticleById () {
        $article = Article::create([
            'title' => 'testing',
            'description' => 'testing too'
        ]);

        $response = $this->getJson('/api/article/' . $article->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'title',
                'description',

            ]
        ]);

    }

    /**
     * @test
     */
    public function shouldReturnModelNotFoundWhenPassingArticleInvalidId () {
        $response = $this->getJson('api/article/5675673462351234235');

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function shouldUpdateCreatedArticleInfos () {
        $article = Article::create([
            'id' => '123',
            'title' => 'any title',
            'description' => 'some description'
        ]);

        $payload = [
            'title' => 'updating title',
            'description' => 'updating description'
        ];

        $response = $this->putJson('/api/article/' . $article->id, $payload);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function shouldReturnJsonWithValidationErrorMessageOnArticleUpdate () {
        $article = Article::create([
            'id' => '321',
            'title' => 'title here',
            'description' => 'description here'
        ]);

        $payload = [
            'title' => 'a',
            'description' => 'b'
        ];

        $response = $this->putJson('/api/article/' . $article->id, $payload);

        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }

    /**
     * @test
     */
    public function shouldDeleteArticle () {
        $article = Article::create([
            'id' => '112',
            'title' => 'any title',
            'description' => 'description here'
        ]);

        $response = $this->deleteJson('/api/article/' . $article->id);

        $response->assertNoContent($status = 200);

    }

    /**
     * @test
     */
    public function shouldReturnModelNotFoundWhenPassingArticleInvalidIdOnDestroy () {
        $response = $this->deleteJson('api/article/5675673462351234235');

        $response->assertNotFound();
    }


}

<?php

namespace Tests\Feature\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_all_news_can_be_fetched()
    {
        $user = User::factory()->create();

        News::factory()->count(3)->create([
            "user_id" => $user
        ]);

        $response = $this->actingAs($user)->get('/api/news/');

        $response
            ->assertStatus(HTTP_SUCCESS)
            ->assertJsonStructure([
                "success",
                "message",
                "data"
            ]);

        $this->assertEquals('News Retrieved Successfully.', data_get($response, 'message'));
    }

    public function test_that_a_single_news_can_be_fetched()
    {
        $user = User::factory()->create();

        $testNews = News::factory()->create([
            "user_id" => $user
        ]);

        $response = $this->actingAs($user)->get("/api/news/{$testNews->id}");

        $response
            ->assertStatus(HTTP_SUCCESS)
            ->assertJsonFragment([
                'title' => $testNews->title,
                'content' => $testNews->content
            ]);
    }

    public function test_that_news_can_be_created()
    {
        $user = User::factory()->create();

        $news = News::factory()->make([
            "user_id" => $user
        ]);

        $response = $this->actingAs($user)->post('/api/news', $news->toArray());

        $response
            ->assertStatus(HTTP_CREATED)
            ->assertJsonFragment([
                'title' => $news->title,
                'content' => $news->content,
                'user_id' => $user->id
            ]);

        $this->assertDatabaseCount('news', 1);
        $this->assertDatabaseHas('news', ["title" => "{$news->title}"]);
    }

    public function test_news_can_be_updated()
    {
        $user = User::factory()->create();

        $news = News::factory()->create([
            "user_id" => $user
        ]);

        $this->assertDatabaseCount('news', 1);
        $this->assertDatabaseHas('news', ["title" => "{$news->title}"]);

        $payload = [
            "title" => "Changed Title"
        ];

        $response = $this->actingAs($user)->put("/api/news/{$news->id}", $payload);

        $response->assertStatus(HTTP_SUCCESS);
        $this->assertDatabaseHas('news', ["title" => "Changed Title"]);
    }

    public function test_a_product_can_be_deleted()
    {
        $user = User::factory()->create();

        $news = News::factory()->create([
            "user_id" => $user
        ]);

        $this->assertDatabaseCount('news', 1);
        $this->assertDatabaseHas('news', ["title" => "{$news->title}"]);

        $response = $this->actingAs($user)->delete("/api/news/{$news->id}");
        $response->assertStatus(HTTP_SUCCESS);

        $this->assertDatabaseCount('news', 0);
        $this->assertDatabaseMissing('news', ["title" => "{$news->title}", "content" => "{$news->content}"]);
    }
}

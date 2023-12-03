<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Services\NewsSources\NewsAPISource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

use Tests\TestCase;

class NewsAPISourceTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function test_fetch_articles_processes_and_saves_articles_to_database()
    {
        $newsSource = new NewsAPISource();
        $articles = $this->getFakeArticles();

        $reflectionMethod = new \ReflectionMethod($newsSource, 'processArticles');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($newsSource, $articles, 1);
        
        $this->assertCount(5, Article::all());
        $this->assertDatabaseHas('articles', [
            'title' => $articles[0]['title'],
            'source_id' => 1,
            'author' => $articles[0]['author'],
            'description' => $articles[0]['description'],
            'category_id' => 1,
            'url_to_image' => $articles[0]['urlToImage'],
            'published_at' => $articles[0]['publishedAt'],
            'content' => $articles[0]['content'],
        ]);
    }


    private function getFakeArticles()
    {
        return [
            [
                'source' => [
                    'id' => 'google-news',
                    'name' => 'Google News'
                ],
                'author' => 'CHIP Online Deutschland',
                'title' => 'Fake Article Title 1',
                'description' => 'Fake Article Description 1',
                'url' => 'https://example.com/article1',
                'urlToImage' => 'https://example.com/image1.png',
                'publishedAt' => '2023-12-02T14:35:33Z',
                'content' => 'Fake Article Content 1'
            ],
            [
                'source' => [
                    'id' => 'google-news',
                    'name' => 'Google News'
                ],
                'author' => 'Bloomberg Línea Latinoamérica',
                'title' => 'Fake Article Title 2',
                'description' => 'Fake Article Description 2',
                'url' => 'https://example.com/article2',
                'urlToImage' => 'https://example.com/image2.png',
                'publishedAt' => '2023-12-02T14:09:07Z',
                'content' => 'Fake Article Content 2'
            ],
            [
                'source' => [
                    'id' => 'google-news',
                    'name' => 'Google News'
                ],
                'author' => 'The New York Times',
                'title' => 'Fake Article Title 3',
                'description' => 'Fake Article Description 3',
                'url' => 'https://example.com/article3',
                'urlToImage' => 'https://example.com/image3.png',
                'publishedAt' => '2023-12-02T14:23:12Z',
                'content' => 'Fake Article Content 3'
            ],
            [
                'source' => [
                    'id' => 'google-news',
                    'name' => 'Google News'
                ],
                'author' => 'The Guardian',
                'title' => 'Fake Article Title 4',
                'description' => 'Fake Article Description 4',
                'url' => 'https://example.com/article4',
                'urlToImage' => 'https://example.com/image4.png',
                'publishedAt' => '2023-12-02T14:17:55Z',
                'content' => 'Fake Article Content 4'
            ],
            [
                'source' => [
                    'id' => 'google-news',
                    'name' => 'Google News'
                ],
                'author' => 'BBC News',
                'title' => 'Fake Article Title 5',
                'description' => 'Fake Article Description 5',
                'url' => 'https://example.com/article5',
                'urlToImage' => 'https://example.com/image5.png',
                'publishedAt' => '2023-12-02T14:31:18Z',
                'content' => 'Fake Article Content 5'
            ],
        ];
    }
}
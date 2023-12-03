<?php

namespace App\Services\NewsSources;

use App\Contracts\NewsSourceInterface;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPISource implements NewsSourceInterface
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
        $this->baseUrl = config('services.newsapi.base_url');
    }

    public function fetchArticles()
    {
        $categories = Category::pluck('name', 'id')->toArray();
        Log::info('Starting article fetch process.');

        try {
            foreach ($categories as $categoryName) {
                // Insert category if not exists
                $category = Category::firstOrCreate(['name' => $categoryName]);

                // Fetch top headlines for the category
                $response = $this->fetchTopHeadlines($categoryName);
                
                if ($response && isset($response['articles'])) {
                    $this->processArticles($response['articles'], $category->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching articles: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function fetchTopHeadlines($category)
    {
        Log::debug("Fetching top headlines for category: $category");
        $response = Http::get($this->baseUrl, [
            'apiKey' => $this->apiKey,
            'category' => $category,
            'language' => 'en'
        ]);
        Log::debug("Response received for $category: ", $response->json());
        return $response->json();
    }

    private function processArticles($articles, $categoryId)
    {
        DB::beginTransaction();
        try {

            foreach ($articles as $articleData) {
                $source = Source::updateOrCreate(['name' => $articleData['source']['name']]);
                Log::debug("Processing article: {$articleData['title']}");
                if($articleData['publishedAt'] === '1970-01-01T00:00:00Z'){
                    continue;
                }
                Article::updateOrCreate(
                    ['url' => $articleData['url']], // Assuming URL is unique
                    [
                        'title' => $articleData['title'],
                        'source_id' => $source->id,
                        'author' => $articleData['author'] ?? null,
                        'description' => $articleData['description'] ?? null,
                        'category_id' => $categoryId,
                        'url_to_image' => $articleData['urlToImage'] ?? null,
                        'published_at' => Carbon::parse($articleData['publishedAt'])->toDateTimeString(),
                        'content' => $articleData['content'] ?? null,
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing articles: ' . $e->getMessage());
            throw $e;
        }
    }
}

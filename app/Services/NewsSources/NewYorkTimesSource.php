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

class NewYorkTimesSource implements NewsSourceInterface {
    protected $apiKey;
    protected $baseUrl;

    public function __construct() {
        $this->apiKey = config('services.nytimes.key');
        $this->baseUrl = config('services.nytimes.base_url');
    }

    public function fetchArticles()
    {
        Log::info('Starting NYT article fetch process.');

        try {
                $response = $this->fetchArticlesFromNYT();
                if (isset($response['response']['docs'])) {
                    $this->saveArticles($response['response']['docs']);
                }
            Log::info('New York Times article fetch process completed.');
        } catch (\Exception $e) {
            Log::error('NYT API Request Error: ' . $e->getMessage());
        }
    }

    private function fetchArticlesFromNYT()
    {
        Log::debug("Fetching articles from NYT");
        $response = Http::get($this->baseUrl, [
            'api-key' => $this->apiKey, // Use the category in the API request
        ]);
        return $response->json();
    }

    private function saveArticles($articles)
    {
        DB::beginTransaction();
        try {
            foreach ($articles as $articleData) {
                $category = Category::firstOrCreate(['name' => $articleData['section_name']]);
                if ($articleData['pub_date'] !== '1970-01-01T00:00:00Z') {
                    $this->createOrUpdateArticle($articleData, $category->id);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing NYT articles: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createOrUpdateArticle($articleData, $categoryId)
    {
        $parsedUrl = parse_url($articleData['web_url']);
        $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $source = Source::firstOrCreate(['name' => $articleData['source']]);
        Log::debug("Processing article: {$articleData['headline']['main']}");
        Article::updateOrCreate(
            ['url' => $articleData['web_url']],
            [
                'title' => $articleData['headline']['main'],
                'description' => $articleData['lead_paragraph'],
                'category_id' => $categoryId,
                'source_id' => $source->id,
                'published_at' => Carbon::parse($articleData['pub_date'])->toDateTimeString(),
                'author' => $articleData['byline']['original'],
                'url_to_image' => isset($articleData['multimedia'][0]) ? $baseUrl.'/'.$articleData['multimedia'][0]['url'] : null,
            ]
        );
    }
}
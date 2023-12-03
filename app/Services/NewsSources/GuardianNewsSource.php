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

class GuardianNewsSource implements NewsSourceInterface
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
        $this->baseUrl = config('services.guardian.base_url');
    }

    public function fetchArticles()
    {
        Log::info('Starting Guardian article fetch process.');

        try {
                $response = $this->fetchArticlesFromGuardian();
                if (isset($response['response']['results'])) {
                    $this->saveArticles($response['response']['results']);
                }
            Log::info('Guardian article fetch process completed.');
        } catch (\Exception $e) {
            Log::error('Guardian API Request Error: ' . $e->getMessage());
        }
    }

    private function fetchArticlesFromGuardian()
    {
        Log::debug("Fetching articles from Guardian for");
        return Http::get($this->baseUrl, [
            'api-key' => $this->apiKey,
            'page-size' => 100
        ])->json();
    }

    private function saveArticles($articles)
    {
        DB::beginTransaction();
        try {
            foreach ($articles as $articleData) {
                $category = Category::firstOrCreate(['name' => $articleData['sectionName']]);
                if ($articleData['webPublicationDate'] !== '1970-01-01T00:00:00Z') {
                    $this->processArticles($articleData, $category->id);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing Guardian articles: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processArticles($articleData, $categoryId)
    {
        $source = Source::firstOrCreate(['name' => 'The Guardian']);
        Log::debug("Processing article: {$articleData['webTitle']} ");
        Article::updateOrCreate(
            ['url' => $articleData['webUrl']],
            [
                'title' => $articleData['webTitle'],
                'category_id' => $categoryId,
                'source_id' => $source->id,
                'published_at' => Carbon::parse($articleData['webPublicationDate'])->toDateTimeString(),
            ]
        );
    }
}

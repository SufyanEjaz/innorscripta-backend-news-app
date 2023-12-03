<?php

namespace App\Repositories;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleNewsRepository
{
    public function fetchArticles(ArticleRequest $request)
    {
        try {
            $query = Article::query();

            // Apply filters
            if ($request->filled('category')) {
                $query->FilterByCategory($request->category);
            }

            if ($request->filled('source')) {
                $query->FilterBySource($request->source);
            }
            
            if ($request->filled('date')) {
                $query->filterByDate($request->date);
            }

            // Apply Search Query 
            if ($request->filled('q')) {
                $query->FilterBySearchQuery($request->q);
            }


            // Apply user preferences
            if ($request->filled('preferred_categories')) {
                $query->FilterByCategoryPreferrence($request->preferred_categories);
            }

            if ($request->filled('preferred_sources')) {
                $query->FilterBySourcePreferrence($request->preferred_sources);
            }

            /*if we have different author table then we can convert these name array to ids. Right now we have auther name in article table
            thats why i assume that frontend will send me auther name array. better approach is if we play with ids instead of names. */
            if ($request->filled('preferred_authors')) {
                $query->FilterByAuthorPreference($request->preferred_authors);
            }                                

            $articles = $query->with(['category', 'source'])->paginate(10);

            return ArticleResource::collection($articles);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
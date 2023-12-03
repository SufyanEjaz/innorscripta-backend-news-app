<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Repositories\ArticleNewsRepository;

class ArticleController extends Controller
{
    private $articleNewsRepository;

    public function __construct(ArticleNewsRepository $articleNewsRepository)
    {
        $this->articleNewsRepository = $articleNewsRepository;
    }

    public function index(ArticleRequest $request) {
        return $this->articleNewsRepository->fetchArticles($request);
    }
}

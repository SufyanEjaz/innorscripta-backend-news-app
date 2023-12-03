<?php

namespace App\Console\Commands;

use App\Services\NewsSources\NewsAPISource;
use Illuminate\Console\Command;

class NewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:newsapi-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News API Data Import';
    private $newsAPISource;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NewsAPISource $newsAPISource)
    {
        parent::__construct();
        $this->newsAPISource = $newsAPISource;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->newsAPISource->fetchArticles();
    }
}

<?php

namespace App\Console\Commands;

use App\Services\NewsSources\NewYorkTimesSource;
use Illuminate\Console\Command;

class NYTNewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:nyt-import';
    private $newYorkTimesSource;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New Yok Times Api Data Import';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NewYorkTimesSource $newYorkTimesSource)
    {
        parent::__construct();
        $this->newYorkTimesSource = $newYorkTimesSource;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->newYorkTimesSource->fetchArticles();
    }
}

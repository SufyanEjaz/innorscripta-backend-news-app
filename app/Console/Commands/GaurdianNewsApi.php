<?php

namespace App\Console\Commands;

use App\Services\NewsSources\GuardianNewsSource;
use Illuminate\Console\Command;

class GaurdianNewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:guardian-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gaurdian News API Data Import';
    private $guardianNewsSource;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GuardianNewsSource $guardianNewsSource)
    {
        parent::__construct();
        $this->guardianNewsSource = $guardianNewsSource;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->guardianNewsSource->fetchArticles();
    }
}

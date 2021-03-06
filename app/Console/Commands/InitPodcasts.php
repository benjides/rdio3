<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Program;

use App\Jobs\PageCrawler;

use Illuminate\Foundation\Bus\DispatchesJobs;


class InitPodcasts extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podcasts:init {program}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start scrapping through the web to fill the databses with initial data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $argv = $this->argument('program');
      $program = Program::where('route','=',$argv)->first();
      $this->info("Crawling : ".$program->name);
      $job = (new PageCrawler($program , $program->link))->onQueue("page");
      $this->dispatch($job);
    }
}

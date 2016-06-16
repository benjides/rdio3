<?php

namespace App\Console\Commands;

use App\Jobs\UpdateCrawler;
use App\Program;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdatePodcasts extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podcasts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the podcasts with the lastest data';

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
        $programs = Program::all();
        foreach($programs as $program){
            $this->info("Updating : ".$program->name);
            $job = (new UpdateCrawler($program,$program->link))->onQueue("podcast");
            $this->dispatch($job);
        }
    }
}

<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\TableCrawler;

use App\Program;
use Goutte\Client;
use Illuminate\Queue\InteractsWithQueue;


class PageCrawler extends Job implements SelfHandling, ShouldQueue
{
    use DispatchesJobs,InteractsWithQueue;

    protected $program;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Program $program , $url)
    {
        $this->program = $program;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $client = new Client();
      $crawler = $client->request( 'GET', $this->url );
      $job = ( new TableCrawler($this->program, $this->url ) )->onQueue('table');
      $this->dispatch($job);
      try {
        $nextPage = $crawler->filter('.siguiente a')->link();
        $job = ( new PageCrawler( $this->program, $nextPage->geturi() ) )->onQueue('page');
        $this->dispatch($job);
      }catch (\Exception $e) {
          $this->delete();
      }
    }
}

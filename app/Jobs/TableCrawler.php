<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\PodcastCrawler;

use App\Program;
use Goutte\Client;
use Illuminate\Queue\InteractsWithQueue;

class TableCrawler extends Job implements SelfHandling, ShouldQueue
{
    use DispatchesJobs,InteractsWithQueue;

    protected $program;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Program $program, $url)
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

        $content = $crawler->filter('.ContentTabla > ul')->children()->reduce(function ($node) {
          if ($node->attr('id') == "tocheader"
          || $node->filter('.col_tip > span')->text() == "Fragmento"
          || $node->filter('.col_tip > span')->text() == "Entrevista"
          || preg_match("/\bentrevista\b/i", $node->filter('.col_tit a')->text())  ) {
            return false;
          }
        });

        for ($i=0; $i < sizeof($content) ; $i++) {
          try {
            $node = $content->eq($i);
            $url = $node->filter('.col_tit a')->link();
            $job = (new PodcastCrawler($this->program,$url->getUri()))->onQueue('podcast');
            $this->dispatch($job);
          } catch (\Exception $e) {
            $this->delete();
          }
        }
    }
}

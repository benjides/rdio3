<?php

namespace App\Jobs;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Program;
use App\Podcast;
use Goutte\Client;
use Carbon\Carbon;


class PodcastCrawler extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue;

    protected $program;

    protected $url;

    /**
     * Create a new job instance.
     *
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
      $dates = array(
        'ene' => 1,'feb' => 2,'mar' => 3,
        'abr' => 4,'may' => 5,'jun' => 6,
        'jul' => 7,'ago' => 8,'sep' => 9,
        'oct' => 10,'nov' => 11,'dic' => 12,
      );
      $client = new Client();
      $crawler = $client->request( 'GET', $this->url );

      try {
        // Title
        $title = rtrim($crawler->filter('#popupEnlazar > span p')->text());

        // Description
        $description = $crawler->filter('#contenidoshowhide .description')->text();

        // Download
        $download = $crawler->filter('meta[name="RTVE.audio"]')->attr('content');

        // Duration
        $str_time = $crawler->filter('meta[name="RTVE.duracion"]')->attr('content');
        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
        $duration = $hours * 3600 + $minutes * 60 + $seconds;

        // Date
        $date =  $crawler->filter('meta[name="RTVE.emission_date"]')->attr('content');
        $regex = "/.+, (\d{2}) (\w{3}) (\d{4}) (.+) .+/";
        preg_match($regex, $date, $matches);
        $date = $matches[3].'-'.$dates[$matches[2]].'-'.$matches[1].' '.$matches[4];
        $date = Carbon::createFromFormat('!Y-m-d H:i:s', $date);

        $podcast = new Podcast ([
          'title' => $title,
          'description' => $description,
          'link' => $download,
          'duration' => $duration,
          'date' => $date
         ]);

         try {
             $this->program->podcasts()->save($podcast);
         } catch (\Exception $e) {
             $this->release();
         }

      } catch (\Exception $e) {
        $this->delete();
      }



    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Program;
use App\Podcast;
use Carbon\Carbon;

use Illuminate\Database\QueryException;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
ini_set('max_execution_time', 3500);

class InitPodcasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podcasts:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start scrapping through the web to fil the databses with initial data.';

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
        #Bjork
        #Chk Chk Chk, Superfood, El Vy y Hot Chip
        $datearr = array(
          'ene' => 1,
          'feb' => 2,
          'mar' => 3,
          'abr' => 4,
          'may' => 5,
          'jun' => 6,
          'jul' => 7,
          'ago' => 8,
          'sep' => 9,
          'oct' => 10,
          'nov' => 11,
          'dic' => 12,
        );
        $programs = Program::get();
        foreach ($programs as $program ) {
          $this->info('Crawling : '.$program->name);
          $client = new Client();
          $crawler = $client->request('GET', $program->link );
          $next = false;
          while(!$next){
            $content = $crawler->filter('.ContentTabla > ul')->children()->reduce(function ($node) {
              if ($node->attr('id') == "tocheader"
              || $node->filter('.col_tip > span')->text() == "Fragmento"
              || $node->filter('.col_tip > span')->text() == "Entrevista"
              || preg_match("/\bentrevista\b/i", $node->filter('.col_tit a')->text())  ) {
                return false;
              }
            });
            for ($i=0; $i < sizeof($content) ; $i++) {
              $node = $content->eq($i);
              $link = $node->filter('.col_tit a')->link();
              $crwlr = $client->click($link);
              // Title

              $title = $crwlr->filter('.header h2')->text();
              $regex = "/(.+)\s*\-\s*(.+)\s*\-\s*(.+)/";
              preg_match($regex, $title, $results);
              $title = rtrim($results[2]);
              // Description
              $description = $crwlr->filter('#contenidoshowhide .description > p')->text();
              // Link
              $download = $node->filter('.col_tip a')->link()->getUri();
              // Duration
              $str_time = $node->filter('.col_dur')->text();
              $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
              sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
              $duration = $hours * 3600 + $minutes * 60 + $seconds;
              // Date
              $date =  $crwlr->filter('.date > h4')->text();
              $regex = '/(\d{2})\s([A-z]+)\s(\d{4})/';
              preg_match($regex, $date, $results);
              $date = $results[1].'-'.$datearr[$results[2]].'-'.$results[3];
              $date = Carbon::createFromFormat('!d-m-Y', $date);

              try {
                $podcast = new Podcast (array(
                  'title' => $title,
                  'description' => $description,
                  'link' => $download,
                  'duration' => $duration,
                  'date' => $date
                ));
                $podcast = $program->podcasts()->save($podcast);
                $this->info('Added : '.$podcast->title);
              } catch (QueryException $e) {
                $next = true;
                $this->info('Changing program');
                break;
              }
            }
            $nextpage = $crawler->filter('.siguiente a')->link();
            $crawler = $client->click($nextpage);
          }
        }
    }
}

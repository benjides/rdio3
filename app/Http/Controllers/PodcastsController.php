<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Program;

use App\Jobs\PageCrawler;
use App\Jobs\UpdateCrawler;

class PodcastsController extends Controller
{
    public function init()
    {
      $programs = Program::all();
      foreach ($programs as $program) {
        $job = ( new PageCrawler($program , $program->link ))->onQueue('page');
        $this->dispatch($job);
      }
      return $programs;
    }
    public function initProgram($program)
    {
      $program = Program::where('route','=',$program)->first();
      $job = ( new PageCrawler($program , $program->link ))->onQueue('page');
      $this->dispatch($job);
      return $program;
    }

    public function update()
    {
      $programs = Program::all();
      foreach ($programs as $program) {
        $job = ( new UpdateCrawler($program , $program->link ))->onQueue('table');
        $this->dispatch($job);
      }
      return $programs;
    }
    public function updateProgram($program)
    {
      $program = Program::where('route','=',$program)->first();
      $job = ( new UpdateCrawler($program , $program->link ))->onQueue('ptable');
      $this->dispatch($job);
      return $program;
    }


}

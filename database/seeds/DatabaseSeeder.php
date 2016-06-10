<?php

use Illuminate\Database\Seeder;
use App\Program;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Program::create([
        'name' => 'Siglo 21',
        'route' => 'siglo-21',
        'link' => 'http://www.rtve.es/alacarta/interno/contenttable.shtml?pbq=1&orderCriteria=DESC&modl=TOC&locale=es&pageSize=15&ctx=2082',
      ]);
      Program::create([
        'name' => '180 Grados',
        'route' => '180-grados',
        'link' => 'http://www.rtve.es/alacarta/interno/contenttable.shtml?pbq=1&orderCriteria=DESC&modl=TOC&locale=es&pageSize=15&ctx=22270',
      ]);
    }
}

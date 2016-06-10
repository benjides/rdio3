<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model {

	protected $table='podcasts';

	protected $fillable = [
		"program_id",
		"title",
		"description",
		"link",
		"duration",
		"date"
  ];
  protected $hidden = ['id','program_id','created_at','updated_at'];
	protected $dates = [
				'date',
        'created_at',
        'updated_at',
    ];
	public function program()
  {
    return $this->belongsTo('App\Program');
  }

}

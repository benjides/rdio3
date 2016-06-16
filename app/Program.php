<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model {
	protected $table='programs';
  protected $fillable = [
    'name',
		'route',
		'link',
  ];
  protected $hidden = ['id','link','created_at','updated_at'];

  public function podcasts()
  {
    return $this->hasMany('App\Podcast');
  }

	public function getRouteAttribute($value)
  {
		return url('/program',$value);
  }

	public function setRouteAttribute($value) {
      $this->attributes['route'] = str_slug($this->name);
  }

}

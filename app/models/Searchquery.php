<?php

class Searchquery extends \Eloquent {

	protected $table = 'searchqueries';
	protected $guarded = ['id'];
	protected $fillable = [];

	public static $rules = [

		'name' => 'unique:searchqueries'

	];

	public function usersearches()
	{
		return $this->hasMany('Usersearch');
	}

	public function articles()
	{
		return $this->belongsToMany('Article');
	}

	public function isStale()
	{
		$seconds_since_last_update = strtotime(\Carbon\Carbon::now())- strtotime($this->updated_at);

		if($seconds_since_last_update > Config::get('hivemind.cache_reddit_requests'))
			return true;

		return false;
	}

}
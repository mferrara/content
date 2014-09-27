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

}
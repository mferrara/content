<?php

class Subreddit extends \Eloquent {

	protected $guarded = ['id'];
	protected $fillable = [];

	public static $rules = [

		'name' => ['unique:subreddits']

	];

	public function articles()
	{
		return $this->hasMany('Article');
	}

	public static function findOrCreate($name)
	{
		$model = self::where('name', $name)->first();

		if($model === null)
		{
			$val = Validator::make(['name' => $name], self::$rules);
			if($val->passes())
				$model = self::create(['name' => $name]);
			else
				dd(get_called_class().' not valid on insert');
		}

		return $model;
	}

}
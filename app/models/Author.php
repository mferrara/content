<?php

class Author extends \Eloquent {

	protected $guarded = ['id'];
	protected $fillable = [];

	public static $rules = [

		'name' => ['unique:authors']

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

    public function incrementArticleCount()
    {
        DB::table('authors')->where('id', $this->id)->increment('article_count');
    }

}
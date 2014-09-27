<?php

class Usersearch extends \Eloquent {

	protected $table = 'usersearches';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function searchquery()
	{
		return $this->belongsTo('Searchquery');
	}

	public static function search(Searchquery $query)
	{
		$scraper = new \HiveMind\Reddit();

		$data = $scraper->Search($query, 5, 'plain', 'relevance', 'all', 'all');

		return Usersearch::create(['searchquery_id' => $query->id]);
	}

}
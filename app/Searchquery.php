<?php

use \Laracasts\Presenter\PresentableTrait;

class Searchquery extends \Eloquent {

    use PresentableTrait;

    protected $presenter = '\HiveMind\Presenters\SearchqueryPresenter';

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

		if($seconds_since_last_update > Config::get('hivemind.cache_reddit_search_requests'))
			return true;

		return false;
	}

    public function queueArticleProcessing()
    {
        $searchquery_id         = $this->id;
        $data['searchquery_id'] = $searchquery_id;
        Queue::push('\HiveMind\Jobs\ProcessArticles@processSearchquery', $data, 'redditprocessing');

        return true;
    }

    public function queueWebhooks()
    {
        $searchquery_id         = $this->id;
        $data['searchquery_id'] = $searchquery_id;
        Queue::push('\HiveMind\Jobs\SendContentWebhooks@send', $data, 'webhooks');

        return true;
    }

}
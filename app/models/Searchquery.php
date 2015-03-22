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
        $searchquery_id = $this->id;
        \Queue::push(function($job) use($searchquery_id)
        {
            $query = \Searchquery::find($searchquery_id);

            \HiveMind\ArticleProcessor::fire($query);

            $query->cached              = 1;
            $query->currently_updated   = 0;
            $query->save();

            $job->delete();

        }, null,'redditprocessing');

        return true;
    }

}
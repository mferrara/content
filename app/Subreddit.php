<?php

use \Laracasts\Presenter\PresentableTrait;

class Subreddit extends \Eloquent
{

    use PresentableTrait;
    protected $presenter = '\HiveMind\Presenters\SubredditPresenter';

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

        if ($model === null) {
            $val = Validator::make(['name' => $name], self::$rules);
            if ($val->passes()) {
                $model = self::create(['name' => $name]);
            } else {
                dd(get_called_class().' not valid on insert');
            }
        }

        return $model;
    }

    public function isStale()
    {
        $seconds_since_last_update = strtotime(\Carbon\Carbon::now())- strtotime($this->updated_at);

        if ($seconds_since_last_update > Config::get('hivemind.cache_reddit_subreddit_requests')) {
            return true;
        }

        return false;
    }

    public function incrementArticleCount()
    {
        DB::table('subreddits')->where('id', $this->id)->increment('article_count');
    }

    public function queueArticleProcessing()
    {
        $subreddit_id           = $this->id;
        $data['subreddit_id']   = $subreddit_id;
        Queue::push('\HiveMind\Jobs\ProcessArticles@processSubreddit', $data, 'redditprocessing');

        return true;
    }
}

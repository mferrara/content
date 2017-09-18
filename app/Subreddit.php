<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use \Laracasts\Presenter\PresentableTrait;

/**
 * Class Subreddit
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $article_count
 * @property int $currently_updating
 * @property int $scraped
 * @property int $cached
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereArticleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCurrentlyUpdating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereUpdatedAt($value)
 */
class Subreddit extends Model
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
        return $this->hasMany(\App\Article::class);
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

        if ($seconds_since_last_update > config('hivemind.cache_reddit_subreddit_requests')) {
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

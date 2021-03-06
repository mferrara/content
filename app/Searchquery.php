<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Queue;
use \Laracasts\Presenter\PresentableTrait;

/**
 * Class Searchquery
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $currently_updating
 * @property int $scraped
 * @property int $cached
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Usersearch[] $usersearches
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCurrentlyUpdating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereUpdatedAt($value)
 */
class Searchquery extends Model
{

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
        return $this->hasMany(\App\Usersearch::class);
    }

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function isStale()
    {
        $seconds_since_last_update = strtotime(\Carbon\Carbon::now())- strtotime($this->updated_at);

        if ($seconds_since_last_update > config('hivemind.cache_reddit_search_requests')) {
            return true;
        }

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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class Article
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $reddit_id
 * @property string $fullname
 * @property string $type
 * @property string $content_type
 * @property int $subreddit_id
 * @property int|null $character_count
 * @property int|null $word_count
 * @property int|null $paragraph_count
 * @property mixed|null $data
 * @property mixed|null $post_text
 * @property mixed|null $post_text_html
 * @property int $author_id
 * @property int $score
 * @property int $ups
 * @property int $downs
 * @property int $nsfw
 * @property string $permalink
 * @property string $url
 * @property int $basedomain_id
 * @property int $created
 * @property int $is_self
 * @property string $title
 * @property string $slug
 * @property int $num_comments
 * @property int $comments_scraped
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Author $author
 * @property-read \App\Basedomain $basedomain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Searchquery[] $searchqueries
 * @property-read \App\Subreddit $subreddit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereBasedomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCharacterCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCommentsScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereDowns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereIsSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereNumComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereParagraphCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePostText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePostTextHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereRedditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSubredditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereWordCount($value)
 */
class Article extends Model
{

    use PresentableTrait;
    protected $presenter = '\HiveMind\Presenters\ArticlePresenter';

    protected $guarded      = ['id'];
    protected $fillable     = [];
    protected $softDelete   = true;

    public static $rules    = [

        'fullname'  => ['unique:articles']

    ];

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(\App\Author::class);
    }

    public function subreddit()
    {
        return $this->belongsTo(\App\Subreddit::class);
    }

    public function basedomain()
    {
        return $this->belongsTo(\App\Basedomain::class);
    }

    public function searchqueries()
    {
        return $this->belongsToMany(\App\Searchquery::class);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = $this->compress($value);
    }

    public function getDataAttribute($value)
    {
        return $this->decompress($value);
    }

    public function setPostTextAttribute($value)
    {
        $this->attributes['post_text'] = $this->compress($value);
    }

    public function getPostTextAttribute($value)
    {
        return $this->decompress($value);
    }

    public function setPostTextHtmlAttribute($value)
    {
        $this->attributes['post_text_html'] = $this->compress($value);
    }

    public function getPostTextHtmlAttribute($value)
    {
        return $this->decompress($value);
    }

    public function compress($value)
    {
        if ($value !== null) {

            // Convert to JSON if it's not already
            if(is_string($value) == false || json_decode($value) == null)
                $value = json_encode($value);

            // Double compress (http://stackoverflow.com/questions/10991035/best-way-to-compress-string-in-php)
            $value = gzdeflate($value, 9);
            $value = gzdeflate($value, 9);
        }

        return $value;
    }

    public function decompress($value)
    {
        if ($value == null) {
            return $value;
        }

        // This string is an object that has been decoded then double gzdeflate()'d - see above - (http://stackoverflow.com/questions/10991035/best-way-to-compress-string-in-php)
        return json_decode(gzinflate(gzinflate($value)));
    }

    public static function getContentType($content)
    {

        if ($content->is_self == true) {
            // Classify selfposts by length
            if ($content->word_count > 350) {
                return 'self.long';
            } elseif ($content->word_count > 150) {
                return 'self.medium';
            } else {
                return 'self.short';
            }
        } elseif ((
                stristr($content->url, "imgur.com/") ||
                stristr($content->url, "vidble.com/") ||
                stristr($content->url, "minus.com/") ||
                stristr($content->url, "twimg.com/") ||
                stristr($content->url, "500px.org/") ||
                stristr($content->url, "500px.com/") ||
                stristr($content->url, "flickr.com/") ||
                stristr($content->url, "tinypic.com/") ||
                stristr($content->url, "upload.wikimedia.org/") ||
                stristr($content->url, "media.tumblr.com/") ||
                stristr($content->url, "fbcdn.net/") ||
                stristr($content->url, "fbcdn.") ||
                strtolower(substr($content->url, -4)) === '.png' ||
                strtolower(substr($content->url, -4)) === '.gif' ||
                strtolower(substr($content->url, -4)) === '.jpg'
            )
        ) {
            return 'image';
        } elseif (stristr($content->url, "wikipedia.org/")) {
            return 'wiki';
        } elseif (stristr($content->url, "youtube.com/") ||
            stristr($content->url, "youtu.be/") ||
            stristr($content->url, "vimeo.com/")) {
            return 'video';
        } elseif (stristr($content->url, "facebook.com/") ||
            stristr($content->url, "twitter.com/") ||
            stristr($content->url, "instagram.com/")) {
            return 'social';
        } elseif (stristr($content->url, "reddit.com/")) {
            return 'self';
        } elseif (stristr($content->url, "wordpress.com") ||
            stristr($content->url, "blogspot.com") ||
            stristr($content->url, "tumblr.com")) {
            return 'blog';
        } elseif (stristr($content->url, "wsj.com/") ||
            stristr($content->url, "forbes.com/") ||
            stristr($content->url, "latimes.com/") ||
            stristr($content->url, "chicagotribune.com/") ||
            stristr($content->url, "huffingtonpost.com/") ||
            stristr($content->url, "bbc.co.uk/") ||
            stristr($content->url, "rt.com/") ||
            stristr($content->url, "bbc.com/") ||
            stristr($content->url, "reuters.com/") ||
            stristr($content->url, "theguardian.com/") ||
            stristr($content->url, "telegraph.co.uk/") ||
            stristr($content->url, "cnn.com/") ||
            stristr($content->url, "kyivpost.com/") ||
            stristr($content->url, "ap.org/") ||
            stristr($content->url, "cbslocal.com/") ||
            stristr($content->url, "news.yahoo.com/") ||
            stristr($content->url, "news.google.com/") ||
            stristr($content->url, "nytimes.com/") ||
            stristr($content->url, "washingtonpost.com/") ||
            stristr($content->url, "independent.co.uk/") ||
            stristr($content->url, "aljazeera.com/") ||
            stristr($content->url, "theatlantic.com/") ||
            stristr($content->url, "usatoday.com/") ||
            stristr($content->url, "spiegel.de/") ||
            stristr($content->url, "businessinsider.com/")) {
            return 'news';
        } elseif (stristr($content->url, "amazon.com/") ||
            stristr($content->url, "walmart.com/") ||
            stristr($content->url, "target.com/") ||
            stristr($content->url, "newegg.com/") ||
            stristr($content->url, "steepandcheap.com/") ||
            stristr($content->url, "rei.com/")) {
            return 'shopping';
        } else {
            return 'other';
        }
    }
}

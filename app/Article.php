<?php

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

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
        return $this->hasMany('Comment');
    }

    public function author()
    {
        return $this->belongsTo('Author');
    }

    public function subreddit()
    {
        return $this->belongsTo('Subreddit');
    }

    public function basedomain()
    {
        return $this->belongsTo('Basedomain');
    }

    public function searchqueries()
    {
        return $this->belongsToMany('Searchquery');
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
            $value = serialize($value);
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

        // This string is an object that has been serialized then double gzdeflate()'d - see above - (http://stackoverflow.com/questions/10991035/best-way-to-compress-string-in-php)
        return unserialize(gzinflate(gzinflate($value)));
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
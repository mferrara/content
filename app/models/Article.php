<?php

class Article extends \Eloquent {

	protected $guarded 		= ['id'];
	protected $fillable 	= [];
	protected $softDelete	= true;

	public static $rules 	= [

		'fullname'	=> ['unique:articles']

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

	public function searchqueries()
	{
		return $this->belongsToMany('Searchquery');
	}

	public static function getContentType($content)
	{

		if($content->is_self == true)
		{
			// Classify selfposts by length
			if($content->word_count > 350)
				return 'self.long';
			elseif($content->word_count > 150)
				return 'self.medium';
			else
				return 'self.short';
		}
		elseif((stristr($content->url, "imgur.com/") ||
				stristr($content->url, "minus.com/") ||
				stristr($content->url, "500px.org/") ||
				stristr($content->url, "500px.com/") ||
				stristr($content->url, "flickr.com/") ||
				stristr($content->url, "tinypic.com/") ||
				stristr($content->url, "upload.wikimedia.org/") ||
				stristr($content->url, "media.tumblr.com/") ||
				stristr($content->url, "fbcdn.net/") ||
				stristr($content->url, "fbcdn."))
			&&
			(
				strtolower(substr($content->url, -4)) === '.jpg' ||
				strtolower(substr($content->url, -4)) === '.gif' ||
				strtolower(substr($content->url, -4)) === '.png'
			)
		)
		{
			return 'image';
		}
		elseif(stristr($content->url, "wikipedia.org/"))
		{
			return 'wiki';
		}
		elseif(	stristr($content->url, "youtube.com/") ||
			stristr($content->url, "youtu.be/") ||
			stristr($content->url, "vimeo.com/"))
		{
			return 'video';
		}
		elseif(	stristr($content->url, "facebook.com/") ||
			stristr($content->url, "twitter.com/") ||
			stristr($content->url, "instagram.com/"))
		{
			return 'social';
		}
		elseif(stristr($content->url, "reddit.com/"))
		{
			return 'self';
		}
		elseif(	stristr($content->url, "wordpress.com") ||
			stristr($content->url, "blogspot.com") ||
			stristr($content->url, "tumblr.com"))
		{
			return 'blog';
		}
		elseif(	stristr($content->url, "wsj.com/") ||
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
			stristr($content->url, "businessinsider.com/"))
		{
			return 'news';
		}
		elseif(	stristr($content->url, "amazon.com/") ||
			stristr($content->url, "walmart.com/") ||
			stristr($content->url, "target.com/") ||
			stristr($content->url, "newegg.com/") ||
			stristr($content->url, "steepandcheap.com/") ||
			stristr($content->url, "rei.com/"))
		{
			return 'shopping';
		}
		else
		{
			return 'other';
		}
	}

}
<?php

namespace HiveMind;

use App\Article;
use App\Author;
use App\Basedomain;
use App\Searchquery;
use App\Subreddit;
use Bugsnag;

class Reddit extends Scraper
{

    var $url                        = "http://www.reddit.com/";
    var $query_parameter            = "q=";
    var $safe_parameter             = "safe=";
    var $sort_parameter             = "sort=";
    var $syntax_parameter           = "syntax=";
    var $time_parameter             = "t=";
    var $pagination_parameter       = "start=";
    var $response_type              = ".json";
    var $restrict_to_sub_parameter  = "restrict_sr=";
    var $limit_parameter            = "limit=";
    var $result_limit               = 100;

    var $search_result_types    = [
                                    "t1" => "Comment",
                                    "t2" => "Account",
                                    "t3" => "Link",
                                    "t4" => "Message",
                                    "t5" => "Subreddit",
                                    "t6" => "Award",
                                    "t8" => "PromoCampaign"
                                    ];

    public function Comments($article_id, $subreddit, $proxy = false, $limit = 10, $sort = 'top')
    {
        $url = $this->url."r/".$subreddit."/comments/".$article_id.$this->response_type."?".
            $this->sort_parameter   .$sort."&".
            $this->limit_parameter  .$limit;

        $content = json_decode($this->request($url));

        $comments = [];
        if (count($content) > 0) {
            foreach ($content as $listing) {
                // If this is a listing, and has some data...
                if ($listing->kind == 'Listing' && count($listing->data)) {
                    $comments = array_merge($comments, Reddit::extractComments($listing));
                }
            }
        }

        return $comments;
    }

    public static function extractComments($listing)
    {
        $comments = [];
        // If there's children...(these should be parent comments)
        if (isset($listing->data->children) && count($listing->data->children)) {
            $parents = $listing->data->children;
            if ($parents[0]->kind == 't3') {
                unset($parents[0]);
            }

            foreach ($parents as $parent) {
                if ($parent->kind == 't1') {
                    $comment = $parent->data;

                    $comment->reddit_id = $comment->id;

                    unset($comment->subreddit_id);
                    unset($comment->banned_by);
                    unset($comment->saved);
                    unset($comment->id);
                    unset($comment->parent_id);
                    unset($comment->approved_by);
                    unset($comment->edited);
                    unset($comment->author_flair_css_class);
                    unset($comment->link_id);
                    unset($comment->score_hidden);
                    unset($comment->author_flair_text);
                    unset($comment->created_utc);
                    unset($comment->distinguished);
                    unset($comment->num_reports);
                    unset($comment->likes);

                    // Do recursive shit here
                    //dd($comment->replies);

                    if (isset($comment->replies->data) && count($comment->replies->data) > 0) {
                        $comments = array_merge($comments, Reddit::extractComments($comment->replies));
                    }

                    $comments[] = $comment;
                }
            }
        }

        // Remove the $comment->replies
        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                unset($comment->replies);
            }
        }

        return $comments;
    }

    public function Subreddit($subreddit, $page_depth = 1, $search_sort = 'top', $search_time = 'all')
    {
        $url = $this->url."r/".$subreddit."/".$search_sort.$this->response_type."?".
            $this->sort_parameter   .$search_sort."&".
            $this->time_parameter   .$search_time."&".
            $this->limit_parameter  .$this->result_limit;

        $sub = Subreddit::where('name', $subreddit)->first();
        $results = [];
        $pages_completed = 0;
        $after = false;
        $request_count = $this->result_limit; // To determine if a search returned was less than max results, if so, don't run the next page page_depth searches
        while ($pages_completed < $page_depth && $request_count == $this->result_limit) {
            // Add the previous request's 'after' token to this one to get the next page of results
            if ($after != false) {
                $url .= "&"."after=".$after;
            }

            // Fetch results
            $result = $this->request($url);
            $content = json_decode($result);

            if ($content == false) {
                \Log::debug('No content while trying to scrape subreddit - '.$subreddit);
                \Log::debug($result);
                throw new Exceptions\NoContentException('No content while trying to scrape subreddit - '.$subreddit.' - Result: '.$result);
            }

            // Acquire "after" parameter for next page request
            if ($page_depth > 1 && isset($content->data->after)) {
                $after = $content->data->after;
            }

            // Add to output array
            if (is_object($content) && is_object($content->data)) {
                if (isset($content->data->children)) {
                    if (count($content->data->children) > 0) {
                        $results[] = $this->ExtractArticles($content, null, $sub);
                    }
                }
            }

            $pages_completed++;

            // Update count of results, to be checked before running through the loop again
            if (isset($content->data->children) && count($content->data->children) > 0) {
                $request_count = count($content->data->children);
            } else {
                $request_count = 0;
            }

            sleep(\config('hivemind.reddit_sleep'));
        }

        return $results;
    }

    public function Search(Searchquery $query, $page_depth = 1, $search_syntax = 'plain', $search_sort = 'relevance', $search_time = 'all', $subreddits = "all")
    {
        $replace = [' ', ','];
        $subreddits = str_replace($replace, "+", $subreddits);

        $url = $this->url."r/".$subreddits."/search".$this->response_type."?".
            $this->query_parameter  .urlencode($query->name)."&".
            $this->sort_parameter   .$search_sort."&".
            $this->time_parameter   .$search_time."&".
            $this->limit_parameter  .$this->result_limit;

        // If it's non-standard search syntax, add it
        // Leaving this on by default (adding the plain attribute) actually breaks the ability to
        // to focus the search on specific subs
        if ($search_syntax !== 'plain') {
            $url .= "&".$this->syntax_parameter .$search_syntax;
        }

        // If we're searching by specific subreddits, restrict the search
        if ($subreddits != "all") {
            $url .= "&".$this->restrict_to_sub_parameter."true";
        }

        $results            = [];
        $pages_completed    = 0;
        $after              = false;
        $request_count = $this->result_limit; // To determine if a search returned was less than max results, if so, don't run the next page page_depth searches
        while ($pages_completed < $page_depth && $request_count == $this->result_limit) {
            // Add the previous request's 'after' token to this one to get the next page of results
            if ($after != false) {
                $url .= "&"."after=".$after;
            }

            // Fetch results
            $result = $this->request($url);
            $content = json_decode($result);

            if ($content == false) {
                \Log::debug('No content while trying to scrape search query - '.$query->name.' URL: '.$url);
                \Log::debug($result);
                \Log::debug(mb_strlen($result));
                \Log::debug($result === false);
                \Log::debug(is_string($result));
                \Log::debug(is_bool($result));
                throw new Exceptions\NoContentException('No content while trying to scrape search query - '.$query->name.' URL: '.$url);
            }

            // Acquire "after" parameter for next page request
            if ($page_depth > 1 && isset($content->data->after)) {
                $after = $content->data->after;
            }

            // Add to output array
            if (is_object($content) && is_object($content->data)) {
                if (isset($content->data->children)) {
                    if (count($content->data->children) > 0) {
                        $results[] = $this->ExtractArticles($content, $query);
                    }
                }
            }

            $pages_completed++;

            // Update count of results, to be checked before running through the loop again
            if (isset($content->data->children) && count($content->data->children) > 0) {
                $request_count = count($content->data->children);
            } else {
                $request_count = 0;
            }

            sleep(\config('hivemind.reddit_sleep'));
        }

        return $results;
    }

    public function ExtractArticles($content, Searchquery $query = null, Subreddit $subreddit = null)
    {
        $results = [];

        foreach ($content->data->children as $post) {
            // Check to see if this article already exists
            $article = Article::whereFullname($post->data->name)->first();
            if ($article !== null && $query !== null) {
                // Exists, add the search query pivot value
                $check = $article->searchqueries()->where('searchquery_id', $query->id)->first();
                if ($check == null) {
                    $article->searchqueries()->attach($query);
                }
            } else {
                // Doesn't exist, add it
                // Process text fields for basic classifying information
                $character_count    = mb_strlen($post->data->selftext);
                if ($character_count > 0) {
                    $word_count     = count(explode(" ", $post->data->selftext));
                    $paragraph_count = count(explode("&lt;br/&gt;", $post->data->selftext_html));
                } else {
                    $word_count     = 0;
                    $paragraph_count = 0;
                }

                $post->data->word_count = $word_count;
                $post->data->paragraph_count = $paragraph_count;

                // Get base domain name
                $remove             = ["www."];
                $domain             = str_replace($remove, "", parse_url($post->data->url));
                if (! isset($domain['host'])) {
                    Bugsnag::notifyError("HostNotThere", "URL: ".$post->data->url);
                    $base_domain    = 'error';
                } else {
                    $base_domain    = $domain['host'];
                }

                if (mb_stristr($base_domain, 'youtu.be')) {
                    $base_domain = "youtube.com";
                }
                if (mb_stristr($base_domain, 'tumblr.com')) {
                    $base_domain = "tumblr.com";
                }
                if (mb_stristr($base_domain, 'blogspot.com')) {
                    $base_domain = "blogspot.com";
                }
                if (mb_stristr($base_domain, 'tinypic.com')) {
                    $base_domain = "tinypic.com";
                }
                if (mb_stristr($base_domain, 'imgur.com')) {
                    $base_domain = "imgur.com";
                }

                $r['reddit_id']         = $post->data->id;
                $r['fullname']          = $post->data->name;
                $r['type']              = $this->search_result_types[$post->kind];
                $r['content_type']      = Article::getContentType($post->data);

                $r['subreddit_id']      = Subreddit::findOrCreate($post->data->subreddit)->id;
                $r['author_id']         = Author::findOrCreate($post->data->author)->id;
                $r['basedomain_id']     = Basedomain::findOrCreate($base_domain)->id;

                $r['character_count']   = $character_count;
                $r['word_count']        = $word_count;
                $r['paragraph_count']   = $paragraph_count;
                $r['post_text']         = $post->data->selftext;
                $r['post_text_html']    = $post->data->selftext_html;
                $r['score']             = $post->data->score;
                $r['ups']               = $post->data->ups;
                $r['downs']             = $post->data->downs;
                $r['nsfw']              = $post->data->over_18;
                $r['permalink']         = $post->data->permalink;
                $r['url']               = $post->data->url;
                $r['created']           = $post->data->created;
                $r['is_self']           = $post->data->is_self;
                $r['title']             = $post->data->title;
                $r['slug']              = \str_slug($post->data->title);
                $r['num_comments']      = $post->data->num_comments;
                $r['data']              = $post->data;

                $val = \Validator::make($r, Article::$rules);
                if ($val->passes()) {
                    $article = Article::create($r);

                    if ($query !== null || $subreddit !== null) {
                        // Increment various things
                        $article->author->incrementArticleCount();
                        $article->basedomain->incrementArticleCount();
                        $article->subreddit->incrementArticleCount();
                    }

                    if ($query !== null) {
                        // Add this article to searchquery relationship
                        $article->searchqueries()->attach($query);
                    }

                    // Add to output array
                    $results[] = $r;
                }
            }
        }

        return $results;
    }
}

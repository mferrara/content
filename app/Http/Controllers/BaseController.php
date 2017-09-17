<?php

class BaseController extends Controller
{

    public function index()
    {
        $recent_searches        = Searchquery::orderBy('updated_at', 'desc')
            ->where('scraped', 1)
            ->take(10)
            ->get();

        $recent_subreddits      = Subreddit::orderBy('updated_at', 'DESC')
            ->where('scraped', 1)
            ->take(10)
            ->get();

        $random_subreddits      = Subreddit::orderByRaw('rand()')
            ->take(10)
            ->get();

        $random_authors         = Author::orderByRaw('rand()')
            ->take(10)
            ->get();

        $top_subreddits         = Subreddit::orderBy('article_count', 'DESC')
            ->take(10)
            ->get();

        $top_domains            = Basedomain::orderBy('article_count', 'DESC')
            ->take(10)
            ->get();

        $top_authors            = Author::orderBy('article_count', 'DESC')
            ->take(10)
            ->get();

        return View::make('index')
            ->with('recent_searches', $recent_searches)
            ->with('recent_subreddits', $recent_subreddits)
            ->with('random_subreddits', $random_subreddits)
            ->with('random_authors', $random_authors)
            ->with('top_subreddits', $top_subreddits)
            ->with('top_domains', $top_domains)
            ->with('top_authors', $top_authors)
            ->with('pending_searches', Searchquery::where('currently_updating', 1)->orWhere('scraped', 0)->count() + Subreddit::where('currently_updating', 1)->count())
            ->with('total_articles', Article::count())
            ->with('total_self', Article::where('is_self', 1)->count())
            ->with('total_authors', Author::count())
            ->with('total_subreddits', Subreddit::count())
            ->with('total_queries', Searchquery::count())
            ->with('total_domains', Basedomain::count())
            ->with('scraped_subreddits', Subreddit::where('scraped', 1)->count())
            ;
    }

    public function search()
    {
        if (!Input::has('q')) {
            return Redirect::to('/');
        }

        $search_keyword = Input::get('q');
        $usersearch     = Usersearch::getSearch($search_keyword);

        $cache_key = 'searchquery_'.$usersearch->searchquery->id.'_processed_data';
        if (Cache::has($cache_key)) {
            $aggregate_data = Cache::get($cache_key);
        } else {
            $aggregate_data = false;
        }

        if ($usersearch->searchquery->articles()->count() > 0) {
            if (Input::has('content_type')) {
                $articles = $usersearch->searchquery
                    ->articles()
                    ->where('content_type', Input::get('content_type'))
                    ->orderBy('score', 'DESC')
                    ->paginate(25);
            } elseif (Input::has('domain')) {
                $basedomain_id = Basedomain::where('name', Input::get('domain'))->first();
                $basedomain_id = $basedomain_id->id;

                if ($basedomain_id !== null) {
                    $articles = $usersearch->searchquery
                        ->articles()
                        ->where('basedomain_id', $basedomain_id)
                        ->orderBy('score', 'DESC')
                        ->paginate(25);
                } else {
                    $articles = false;
                }
            } elseif (Input::has('subreddit')) {
                $subreddit_id = Subreddit::where('name', Input::get('subreddit'))->first();
                $subreddit_id = $subreddit_id->id;

                if ($subreddit_id !== null) {
                    $articles = $usersearch->searchquery
                        ->articles()
                        ->where('subreddit_id', $subreddit_id)
                        ->orderBy('score', 'DESC')
                        ->paginate(25);
                } else {
                    $articles = false;
                }
            } elseif (Input::has('author')) {
                $author_id = Author::where('name', Input::get('author'))->first();
                $author_id = $author_id->id;

                if ($author_id !== null) {
                    $articles = $usersearch->searchquery
                        ->articles()
                        ->where('author_id', $author_id)
                        ->orderBy('score', 'DESC')
                        ->paginate(25);
                } else {
                    $articles = false;
                }
            } else {
                $articles = $usersearch->searchquery
                    ->articles()
                    ->orderBy('score', 'DESC')
                    ->paginate(25);
            }
        } else {
            $articles = false;
        }

        if ($usersearch->searchquery->currently_updating == 1) {
            $currently_updating = true;
        } else {
            $currently_updating = false;
        }

        return View::make('searchresults')
            ->with('usersearch', $usersearch)
            ->with('articles', $articles)
            ->with('aggregate_data', $aggregate_data)
            ->with('currently_updating', $currently_updating);
    }
}

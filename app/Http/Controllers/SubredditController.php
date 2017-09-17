<?php

namespace App\Http\Controllers;

use App\Subreddit;
use App\Usersearch;
use Illuminate\Support\Facades\Cache;


class SubredditController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /subreddit
     *
     * @return Response
     */
    public function index()
    {
        $subreddits = Subreddit::orderBy('article_count', 'DESC')
            ->paginate(25);

        return view('subreddits.index')
            ->with('subreddits', $subreddits);
    }

    /**
     * Show the form for creating a new resource.
     * GET /subreddit/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /subreddit
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /subreddit/{id}
     *
     * @param  string  $name
     * @return Response
     */
    public function show($name)
    {
        $subreddit = Subreddit::where('name', $name)
            ->first();

        if ($subreddit == null) {
            $subreddit = Subreddit::findOrCreate($name);
        }

        $usersearch = Usersearch::getSubreddit($subreddit->name);

        $cache_key = strtolower(get_class($subreddit)).'_'.$usersearch->subreddit->id.'_processed_data';
        if (Cache::has($cache_key)) {
            $aggregate_data = Cache::get($cache_key);
        } else {
            $aggregate_data = false;
        }

        if ($usersearch->subreddit->articles()->count() > 0) {
            $articles = $usersearch->subreddit->articles()->orderBy('score', 'DESC')->paginate(25);
        } else {
            $articles = false;
        }

        if ($usersearch->subreddit->currently_updating == 1) {
            $currently_updating = true;
        } else {
            $currently_updating = false;
        }

        return view('subreddit')
            ->with('articles', $articles)
            ->with('subreddit', $subreddit)
            ->with('aggregate_data', $aggregate_data)
            ->with('currently_updating', $currently_updating);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /subreddit/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /subreddit/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /subreddit/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

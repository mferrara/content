<?php

namespace App\Http\Controllers;

use App\Searchquery;
use App\Subreddit;


class SearchqueryController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /searchquery
     *
     * @return Response
     */
    public function index()
    {
        $searches = Searchquery::orderBy('name', 'asc')->paginate(100);

        return view('searches')
            ->with('searches', $searches);
    }

    public function pending()
    {
        $pending_queries = Searchquery::where('currently_updating', 1)
                ->get();

        $pending_subreddits = Subreddit::where('currently_updating', 1)
                ->get();

        return view('searchquery.pending')
                ->with('pending_queries', $pending_queries)
                ->with('pending_subreddits', $pending_subreddits);
    }

    /**
     * Show the form for creating a new resource.
     * GET /searchquery/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /searchquery
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /searchquery/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /searchquery/{id}/edit
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
     * PUT /searchquery/{id}
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
     * DELETE /searchquery/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

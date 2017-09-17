<?php

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /article
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /article/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /article
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /article/{id}
     *
     * @param  string  $fullname
     * @return Response
     */
    public function show($fullname)
    {
        $article = Article::where('fullname', $fullname)
            ->with(['author', 'subreddit'])
            ->first();

        return view('articles.show')
            ->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /article/{id}/edit
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
     * PUT /article/{id}
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
     * DELETE /article/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

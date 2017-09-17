<?php

class AuthorController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /author
     *
     * @return Response
     */
    public function index()
    {
        $authors = Author::orderBy('article_count', 'DESC')->paginate(25);

        return view('authors.index')->with('authors', $authors);
    }

    /**
     * Show the form for creating a new resource.
     * GET /author/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /author
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /author/{id}
     *
     * @param  string  $name
     * @return Response
     */
    public function show($name)
    {
        $author = Author::where('name', $name)
            ->first();

        $articles = $author->articles()->orderBy('score', 'DESC')->paginate(25);

        return view('author')
            ->with('articles', $articles)
            ->with('author', $author);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /author/{id}/edit
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
     * PUT /author/{id}
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
     * DELETE /author/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

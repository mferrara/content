<?php

class SearchqueryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /searchquery
	 *
	 * @return Response
	 */
	public function index()
	{
        $searches = Searchquery::orderBy('name', 'asc')->paginate(25);

        return View::make('searches')
            ->with('searches', $searches);
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
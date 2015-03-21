<?php

class BasedomainController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /basedomain
	 *
	 * @return Response
	 */
	public function index()
	{
		$domains = Basedomain::orderBy('id', 'ASC')->paginate(25);

        return View::make('domains.index')
            ->with('domains', $domains);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /basedomain/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /basedomain
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /basedomain/{id}
	 *
	 * @param  string  $name
	 * @return Response
	 */
	public function show($name)
	{
        $domain = Basedomain::whereName($name)->first();

        $articles = $domain->articles()
                ->orderBy('score', 'DESC')
                ->paginate(25);

        return View::make('domain')
            ->with('domain', $domain)
            ->with('articles', $articles);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /basedomain/{id}/edit
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
	 * PUT /basedomain/{id}
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
	 * DELETE /basedomain/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
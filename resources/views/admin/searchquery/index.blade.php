@extends('layouts.admin')

@section('title')
    Admin Searchqueries - keywords.legit.php
@stop

@section('content')
    <h1>Searchqueries  <small>{!! number_format(Searchquery::count()) !!}</small></h1>
    <hr/>
    <div class='row'>
        <div class='col-md-12 col-xs-12'>
            @if(count($searches) > 0)
                <table class='table table-condensed'>
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Query</th>
                        <th class="text-center">In Progress</th>
                        <th class="text-center">Scraped</th>
                        <th class="text-right">Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($searches as $query)
                        <tr>
                            <td class="text-center">{!! $query->id !!}</td>
                            <td>{!! $query->present()->link() !!}</td>
                            <td class="text-center">{!! $query->currently_updating == 1 ? 'Yes' : 'No' !!}</td>
                            <td class="text-center">{!! $query->scraped == 1 ? 'Yes' : 'No' !!}</td>
                            <td class="text-right">{!! $query->updated_at->diffForHumans() !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">{!! $searches->render() !!}</div>
            @endif
        </div>
    </div>
@stop
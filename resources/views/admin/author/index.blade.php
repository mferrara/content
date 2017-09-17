@extends('layouts.admin')

@section('title')
    Admin Authors - keywords.legit.php
@stop

@section('content')
    <h1>Authors <small>{!! number_format(Author::count()) !!}</small></h1>
    <hr/>
    <div class='row'>
        <div class='col-md-12 col-xs-12'>
            @if(count($authors) > 0)
                <table class='table table-condensed'>
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Author</th>
                        <th class="text-right">Article Count</th>
                        <th class="text-right">Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($authors as $author)
                        <tr>
                            <td class="text-center">{!! $author->id !!}</td>
                            <td>{!! $author->present()->link() !!}</td>
                            <td class="text-right">{!! number_format($author->article_count) !!}</td>
                            <td class="text-right">{!! $author->updated_at->diffForHumans() !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">{!! $authors->links() !!}</div>
            @endif
        </div>
    </div>
@stop
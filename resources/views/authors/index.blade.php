@extends('layouts.default')

@section('content')

    {!! link_to('/', 'Home', ['class' => 'btn btn-primary']) !!}

    <h1>Authors <small>{!! number_format(Author::count()) !!} total</small></h1>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Author</th>
            <th>Articles</th>
        </tr>
        </thead>
        <tbody>
        @foreach($authors as $author)
            <tr class=" @if($author->scraped == 1) success @endif " >
                <td>{!! $author->present()->link() !!}</td>
                <td>{!! number_format($author->articles()->count()) !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center">{!! $authors->render() !!}</div>

@stop
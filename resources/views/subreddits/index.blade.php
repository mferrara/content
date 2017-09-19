@extends('layouts.default')

@section('content')

    {!! link_to('/', 'Home', ['class' => 'btn btn-primary']) !!}

    <h1>Subreddits <small>{!! number_format(App\Subreddit::count()) !!} total</small></h1>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Sub</th>
            <th class="text-center">Has been dug into</th>
            <th>Articles</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subreddits as $subreddit)
            <tr class=" @if($subreddit->scraped == 1) success @endif " >
                <td>{!! $subreddit->present()->link() !!}</td>
                <td class="text-center">{!! $subreddit->scraped == 1 ? 'Yes' : 'No' !!}</td>
                <td>{!! number_format($subreddit->articles()->count()) !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center">{!! $subreddits->render() !!}</div>

@stop
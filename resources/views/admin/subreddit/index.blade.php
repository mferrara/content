@extends('layouts.admin')

@section('title')
    Admin Subreddits - keywords.legit.php
@stop

@section('content')
    <h1>Subreddits <small>{!! number_format(App\Subreddit::count()) !!}</small></h1>
    <hr/>
    <div class='row'>
        <div class='col-md-12 col-xs-12'>
            @if(count($subreddits) > 0)
                <table class='table table-condensed'>
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Subreddit</th>
                        <th class="text-right">Article Count</th>
                        <th class="text-center">In Progress</th>
                        <th class="text-center">Scraped</th>
                        <th class="text-right">Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subreddits as $subreddit)
                        <tr>
                            <td class="text-center">{!! $subreddit->id !!}</td>
                            <td>{!! $subreddit->present()->link() !!}</td>
                            <td class="text-right">{!! number_format($subreddit->article_count) !!}</td>
                            <td class="text-center">{!! $subreddit->currently_updating == 1 ? 'Yes' : 'No' !!}</td>
                            <td class="text-center">{!! $subreddit->scraped == 1 ? 'Yes' : 'No' !!}</td>
                            <td class="text-right">{!! $subreddit->updated_at->diffForHumans() !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">{!! $subreddits->render() !!}</div>
            @endif
        </div>
    </div>
@stop
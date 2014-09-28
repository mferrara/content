@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Subreddit <small>{{ $subreddit->name }}</small></h1>

@include('partials.articles-table')

@stop
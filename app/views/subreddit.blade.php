@extends('layouts.default')

@section('content')

<h1>Subreddit <small>{{ $subreddit->name }}</small></h1>

@include('partials.articles-table')

@stop
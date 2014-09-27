@extends('layouts.default')

@section('content')

<h1>Author <small>{{ $author->name }}</small></h1>

@include('partials.articles-table')

@stop
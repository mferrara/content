@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Author <small>{{ $author->name }}</small></h1>
<hr/>

@include('partials.articles-table')

@stop
@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Domain <small>{{ $domain->name }}</small></h1>

@include('partials.articles-table')

@stop
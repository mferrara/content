@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Subreddit <small>{{ $subreddit->name }}</small></h1>
<hr/>

@if($aggregate_data != false)
	@include('partials.aggregate-display')
@else
	<div class="row">
    	<div class="col-md-2">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-4">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center">Processing...</p>
    	</div>
    </div>
@endif

@if($currently_updating == true)
	<p class="lead alert alert-warning text-center">Results being collected/updated @if($articles != false)...here's what we've got so far @else ...please check back in a few minutes @endif</p>
@endif

@include('partials.articles-table')

@stop
@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Searches <small>{{ count($searches) }}</small></h1>

<div class="row">
	@foreach($searches as $key => $search)
        @if($key == 0 || $key == 25 || $key == 50 || $key == 75)
            <div class="col-md-3">
                <div class="row">
        @endif
                    <div class="col-md-8 text-right">{{ link_to('search?q='.urlencode($search->name), $search->name) }}</div>
                    <div class="col-md-4 text-left">
                        @if($search->scraped == 0)
                            <span class="label label-danger">Pending</span>
                        @elseif($search->currently_updating == 1)
                            <span class="label label-warning">Updating</span>
                        @elseif($search->isStale())
                            <span class="label label-default">Stale</span>
                        @else
                            <span class="label label-success">Complete</span>
                        @endif
                    </div>
        @if($key == 24 || $key == 49 || $key == 74 || $key == count($searches) - 1)
                </div>
            </div>
        @endif
	@endforeach
</div>

<div class="text-center">{{ $searches->links() }}</div>

@stop
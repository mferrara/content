@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Post <small>{{ $article->name }}</small></h1>
<hr/>

{{ var_dump($article->toArray()) }}

@stop
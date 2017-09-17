@extends('layouts.default')

@section('content')

    {!! link_to('/', 'Home', ['class' => 'btn btn-primary']) !!}

    <h1>Domains <small>{!! count($domains) !!}</small></h1>

    <table class="table table-condensed">
    	<thead>
    		<tr>
    			<th>Domain</th>
                <th>Articles</th>
    		</tr>
    	</thead>
    	<tbody>
    		@foreach($domains as $domain)
                <tr>
                    <td>{!! $domain->present()->link() !!}</td>
                    <td>{!! number_format($domain->articles()->count()) !!}</td>
                </tr>
            @endforeach
    	</tbody>
    </table>

    <div class="text-center">{!! $domains->render() !!}</div>

@stop
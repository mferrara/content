@extends('layouts.admin')

@section('title')
    Admin Domains - keywords.legit.php
@stop

@section('content')
    <h1>Domains  <small>{!! number_format(Basedomain::count()) !!}</small></h1>
    <hr/>
    <div class='row'>
        <div class='col-md-12 col-xs-12'>
            @if(count($domains) > 0)
                <table class='table table-condensed'>
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Domain</th>
                        <th class="text-right">Article Count</th>
                        <th class="text-right">Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($domains as $domain)
                        <tr>
                            <td class="text-center">{!! $domain->id !!}</td>
                            <td>{!! $domain->present()->link() !!}</td>
                            <td class="text-right">{!! number_format($domain->article_count) !!}</td>
                            <td class="text-right">{!! $domain->updated_at->diffForHumans() !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">{!! $domains->render() !!}</div>
            @endif
        </div>
    </div>
@stop
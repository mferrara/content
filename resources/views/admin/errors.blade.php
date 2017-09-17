@extends('layouts.admin')

@section('title')
    Admin Errors - keywords.legit.php
@stop

@section('content')
    <h1>Errors</h1>
    <div class='row'>
        <div class='col-md-12 col-xs-12'>
            @if(count($errors) > 0)
                <table class='table table-condensed'>
                    <thead>
                    <tr>
                        <th>Error</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($errors as $error)
                        <tr>
                            <td>
                                <div>{!! $error['error'] !!}</div>
                                <div style='display: none;'>{!! $error['stack_trace'] !!}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop
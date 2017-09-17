<meta charset="utf-8">
<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
@yield('meta')
<title>@yield('title')</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">

{{ HTML::style('css/flatly.min.css') }}
{{ HTML::style('css/sticky-footer.css') }}

{{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}

<meta name="csrf-token" content="<?php echo csrf_token() ?>">
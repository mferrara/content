<!doctype html>
<html lang="en-US">
	<head>
		@include('layouts.includes.head')
	</head>
	<body>

        <div class="container-fluid">
            <div class="text-right">
                {!! link_to('admin', 'admin') !!}
            </div>
        </div>

        <br/>
		<div class="container">
			@include('flash::message')

			@yield('content')
		</div>

		@include('layouts.includes.footer')
		@include('layouts.includes.js')
	</body>
</html>

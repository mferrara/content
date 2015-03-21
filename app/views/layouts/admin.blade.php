<!doctype html>
<html lang="en-US">
	<head>
		@include('layouts.includes.head')
	</head>
	<body>

        <div class="container-fluid">
            <div class="text-right">
                {{ link_to('/', 'home') }}
            </div>
        </div>

        <br/>
		<div class="container">
			@include('flash::message')

            <div class="row">
                <div class="col-md-2">
                    <br/>
                    <br/>
                    @include('admin.partials.admin-menu')
                </div>
                <div class="col-md-10">
                    @yield('content')
                </div>
            </div>
		</div>

		@include('layouts.includes.footer')
		@include('layouts.includes.js')
	</body>
</html>

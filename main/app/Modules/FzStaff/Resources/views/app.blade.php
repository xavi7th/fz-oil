<!DOCTYPE html>
<html>

<head>

	@routes(['fzstaff', 'public', 'auth'])

	<meta charset="utf-8" />
	<meta name="robots" content="index, follow" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="format-detection" content="telephone=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="{{$metaDesc ?? 'Sales of phones etc '}}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ $ogUrl ?? route('auth.login') }}" />
	<meta property="og:title" content="{{ $title ?? ''}} | {{config('app.name') }}" />
	<meta property="og:description" content="{{$metaDesc ?? 'Sales of phones etc '}}" />
	<meta property="og:image" content="{{ asset($ogImg ?? '/img/the-elects-logo.png') }}" />

	<link rel="canonical" href="{{ $cononical ?? route('app.home')}}" />
	<link rel="icon" type="image/png" href="{{ asset('/img/favicon.png') }}">


	<script src="{{ mix('js/dashboard-app-vendor.js') }}" async defer></script>
	<script src="{{ mix('/js/manifest.js') }}" defer></script>
	<script src="{{ mix('/js/vendor.js') }}" defer></script>
	<script src="{{ mix('js/dashboard-app.js') }}" defer></script>
  <link rel="stylesheet" href="{{ mix('css/dashboard-app.css') }}">
</head>

<body data-spy="scroll" data-target=".rui-page-sidebar" data-offset="140"
	class="rui-no-transition rui-navbar-autohide rui-section-lines">
	@inertia

</body>

</html>

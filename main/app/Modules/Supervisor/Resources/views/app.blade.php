<!DOCTYPE html>
<html>

<head>
	@routes(['supervisor', 'salesrep', 'public', 'auth', 'generic'])

	<title>{{ config('app.name') }} | Welcome</title>
	<meta charset="utf-8">
	<meta content="ie=edge" http-equiv="x-ua-compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="favicon.png" rel="shortcut icon">
	<link href="apple-touch-icon.png" rel="apple-touch-icon">
</head>

<body
	class="{{ isset($isAuth) ? 'auth-wrapper' : 'menu-position-side menu-side-left full-screen with-content-panel' }}">
	@inertia

	<script src="{{ mix('js/dashboard-app-vendor.js') }}" async defer></script>
	<script src="{{ mix('/js/manifest.js') }}" defer></script>
	<script src="{{ mix('/js/vendor.js') }}" defer></script>
	<script src="{{ mix('js/dashboard-app.js') }}" defer></script>
	<link rel="stylesheet" href="{{ mix('css/dashboard-app.css') }}">

</body>

</html>

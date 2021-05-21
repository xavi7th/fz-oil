<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	@routes(['superadmin', 'auth'])

  <title>Admin Dashboard HTML Template</title>
	<meta charset="utf-8">
	<meta content="ie=edge" http-equiv="x-ua-compatible">
	<meta content="template language" name="keywords">
	<meta content="Tamerlan Soziev" name="author">
	<meta content="Admin dashboard html template" name="description">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="favicon.png" rel="shortcut icon">
	<link href="apple-touch-icon.png" rel="apple-touch-icon">
</head>

<body class="menu-position-side menu-side-left full-screen with-content-panel">

	@inertia

  <script src="{{ mix('js/dashboard-app-vendor.js') }}" async defer></script>
	<script src="{{ mix('/js/manifest.js') }}" defer></script>
	<script src="{{ mix('/js/vendor.js') }}" defer></script>
	<script src="{{ mix('js/superadmin.js') }}" defer></script>
	<link rel="stylesheet" href="{{ mix('css/dashboard-app.css') }}">
	<link rel="stylesheet" href="{{ mix('css/superadmin-app.css') }}">

</body>

</html>

<!DOCTYPE html>
<html>

<head>
  <title>{{ $title ?? ''}} | {{config('app.name') }}</title>

  <meta charset="utf-8" />
  <meta name="robots" content="index, follow" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="format-detection" content="telephone=yes">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="{{$metaDesc ?? 'Sales of phones etc '}}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ $ogUrl ?? rescue(fn() => route('app.home')) }}" />
  <meta property="og:title" content="{{ $title ?? ''}} | {{config('app.name') }}" />
  <meta property="og:description" content="{{$metaDesc ?? 'Sales of phones etc '}}" />
  <meta property="og:image" content="{{ asset($ogImg ?? '/img/the-elects-logo.png') }}" />

  @routes('public')

  <link rel="canonical" href="{{ $cononical ?? rescue(fn() => route('app.home'))}}" />
  <link rel="icon" type="image/png" href="{{ asset('/img/favicon.png') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">

</head>

<body>
  @inertia

  <script src="{{ mix('js/public-vendor.js') }}" async></script>
  <script src="{{ mix('js/manifest.js') }}" defer></script>
  <script src="{{ mix('js/vendor.js') }}" defer></script>
  <script src="{{ mix('js/app.js') }}" defer></script>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

</body>

</html>

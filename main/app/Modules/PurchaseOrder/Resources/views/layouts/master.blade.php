
<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} | Daily Reports</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta content="{{ config('app.name') }} | Daily Reports" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="favicon.png" rel="shortcut icon">
    <link href="apple-touch-icon.png" rel="apple-touch-icon">
    <link href="//fast.fonts.net/cssapi/487b73f1-c2d1-43db-8526-db577e4c822b.css" rel="stylesheet" type="text/css">

    @yield('customCss')

</head>

<body class="menu-position-side menu-side-left full-screen with-content-panel">
    <div class="all-wrapper with-side-panel solid-bg-all">
        <div class="layout-w">
            <div class="content-w">
                <div class="content-i">
                    <div class="content-box">
                        @yield('contents')
                    </div>
                </div>
            </div>
        </div>
        <div class="display-type"></div>
    </div>


</body>

</html>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ogani | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/client/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/client/css/style.css') }}" type="text/css">
</head>

<body>
    @include('client.pages.header')

    <!-- Hero Section Begin -->
    @include('client.pages.hero-section')
    <!-- Hero Section End -->

    @yield('content')

    <!-- Footer Section Begin -->
    @include('client.pages.footer')
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{{ asset('assets/client/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/client/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('js-custom')
</body>

</html>

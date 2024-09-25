<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barta App | @yield('title', 'A Simple Social Web App')</title>

    @include('partials.head-scripts')

    @include('partials.head-links')

    <!-- Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">
    @include('partials.header')

    @include('partials.notifications')

    @yield('content')

    @include('partials.footer')

    @include('partials.footer-scripts')
</body>

</html>

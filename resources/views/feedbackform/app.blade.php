<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Feedback Form')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
    @livewireStyles

    {{-- ✅ Include pushed styles --}}
    @stack('styles')
    @stack('scripts')
</head>
<body>
    {{-- Page content --}}
    @yield('content')

    {{-- ✅ Include pushed scripts --}}
    @stack('scripts')

    @livewireScripts
</body>
</html>

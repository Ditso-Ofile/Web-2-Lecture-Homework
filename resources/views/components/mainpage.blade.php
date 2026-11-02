{{--
    TASK 2 - Master layout component (Dynamic layout design with Blade Components)
    Every page uses it like this:
        <x-mainpage>
            <x-slot:title>Page title</x-slot:title>
            ... page content ...
        </x-mainpage>
    Theme: Bootswatch "Lux" (free, MIT licence, responsive, based on Bootstrap 5)
    https://bootswatch.com/lux/
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | ' : '' }}Hungary Towns Explorer</title>

    {{-- Bootswatch Lux theme (Bootstrap 5) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Our own small additions to the theme --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    {{-- Pages can push extra <head> content (e.g. Chart.js) with @push('head') --}}
    @stack('head')
</head>
<body class="d-flex flex-column min-vh-100">

    @include('header')

    <main class="flex-grow-1">
        {{-- Flash messages (success / error) shown on every page --}}
        @if (session('success') || session('failed'))
            <div class="container mt-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('failed'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('failed') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        @endif

        {{ $slot }}
    </main>

    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

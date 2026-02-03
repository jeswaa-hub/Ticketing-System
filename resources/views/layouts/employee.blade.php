<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Employee') • Ticketing System</title>
    <script>
        (() => {
            const storageKey = 'employee_theme';
            let theme = 'dark';
            try {
                theme = localStorage.getItem(storageKey) || 'dark';
            } catch (e) {}
            if (theme !== 'light' && theme !== 'dark') {
                theme = 'dark';
            }
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('layouts.alerts')
<body class="bg-slate-950 text-slate-100 font-sans antialiased">
    @yield('content')
</body>
</html>

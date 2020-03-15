<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield ('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-gray-300">
    <div class="flex flex-col min-h-screen">
        <header class="shadow bg-white p-4">
            <nav class="md:mx-16 flex items-center justify-between">
                <a href="/">Dompet</a>
                <a href="/login" class="border-2 border-black border-solid rounded p-2">Masuk</a>
            </nav>
        </header>
        <main class="flex flex-col flex-auto">
            <div class="m-auto">
                <h1 class="text-3xl mb-4">Pencatat Transaksi Pribadi</h1>
                <a href="/register" class="border-2 border-orange-500 p-2 border-solid rounded block text-center text-white bg-orange-500 hover:bg-orange-600 font-bold">Daftar Dompet</a>
            </div>
        </main>
        <footer class="text-sm text-center font-bold">
            &copy; 2018 Nafies Luthfi
        </footer>
    </div>
</body>
</html>

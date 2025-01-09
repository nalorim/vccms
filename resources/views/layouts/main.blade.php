<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="{{ url('/webfonts.css') }}">
        <style>
            [x-cloak] { display: none !important; }
        </style>

    </head>
    <body class="bg-slate-800 text-white">

        <x-navbar></x-navbar>

        @auth
            <livewire:menu />
        @endauth
        
        <div class="max-w-screen-xl mx-auto pb-[100px]">
            
            @yield('content')

        </div>

        <div x-data="{ popup: false}">
            <div @click.outside="popup = false" class="absolute bottom-[50px] right-[50px]">
                <button @click="popup = !popup" class="btn block w-[80px] aspect-square rounded-full bg-slate-600 hover:bg-slate-900">
                    +
                </button>
                <div x-cloak x-show="popup" class="absolute bottom-[110%] right-0 w-[200px] grid grid-cols-1 gap-4">
                    <a href="{{ route('orders.create') }}" class="bg-slate-600 p-2 btn hover:bg-slate-900 whitespace-nowrap">
                        New Order
                    </a>
                    <a href="{{ route('customers.index') }}" class="bg-slate-600 p-2 btn hover:bg-slate-900 whitespace-nowrap">
                        New Customer
                    </a>
                    <a href="{{ route('items.create') }}" class="bg-slate-600 p-2 btn hover:bg-slate-900 whitespace-nowrap">
                        New Item
                    </a>
                    <a href="{{ route('stockins.create') }}" class="bg-slate-600 p-2 btn hover:bg-slate-900 whitespace-nowrap">
                        New PO
                    </a>
                </div>
            </div>
        </div>
        
        
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Freelance Job & Invoice Tracker' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 font-sans text-zinc-900">
    <div class="border-b border-zinc-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ auth()->check() ? route('clients.index') : route('login') }}" class="text-base font-semibold text-zinc-950">
                Freelance Tracker
            </a>
            @auth
                <nav class="flex items-center gap-2 text-sm">
                    @can('dashboard.view')
                        <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">Dashboard</a>
                    @endcan
                    @can('clients.view')
                        <a href="{{ route('clients.index') }}" class="rounded-md px-3 py-2 {{ request()->routeIs('clients.*') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">Clients</a>
                    @endcan
                    @can('projects.view')
                        <a href="{{ route('projects.index') }}" class="rounded-md px-3 py-2 {{ request()->routeIs('projects.*') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">Projects</a>
                    @endcan
                    @can('invoices.view')
                        <a href="{{ route('invoices.index') }}" class="rounded-md px-3 py-2 {{ request()->routeIs('invoices.*') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">Invoices</a>
                    @endcan
                    @can('reports.view')
                        <a href="{{ route('reports.income') }}" class="rounded-md px-3 py-2 {{ request()->routeIs('reports.*') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">Reports</a>
                    @endcan
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="rounded-md px-3 py-2 text-zinc-700 hover:bg-zinc-100">Logout</button>
                    </form>
                </nav>
            @endauth
        </div>
    </div>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>

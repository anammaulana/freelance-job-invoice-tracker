<x-layouts.app title="Login">
    <div class="mx-auto max-w-md">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-zinc-950">Login</h1>
            <p class="mt-2 text-sm text-zinc-600">Masuk untuk mengelola client dan project.</p>
        </div>

        <form action="{{ route('login.store') }}" method="POST" class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                <input id="password" name="password" type="password" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
            </div>
            <label class="flex items-center gap-2 text-sm text-zinc-700">
                <input type="checkbox" name="remember" value="1" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                Remember me
            </label>
            <button class="w-full rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Login</button>
        </form>
    </div>
</x-layouts.app>

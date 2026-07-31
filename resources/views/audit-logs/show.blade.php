<x-layouts.app title="Audit Log Detail">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Audit Log Detail</h1>
            <p class="mt-1 text-sm text-zinc-600">{{ class_basename($auditLog->target_type) }} #{{ $auditLog->target_id }}</p>
        </div>
        <a href="{{ route('audit-logs.index') }}" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Back</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-lg border border-zinc-200 bg-white p-5 lg:col-span-1">
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="font-medium text-zinc-500">Time</dt>
                    <dd class="mt-1 text-zinc-950">{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-zinc-500">Actor</dt>
                    <dd class="mt-1 text-zinc-950">{{ $auditLog->actor_name ?? 'System' }}</dd>
                    @if ($auditLog->actor_email)
                        <dd class="text-xs text-zinc-500">{{ $auditLog->actor_email }}</dd>
                    @endif
                </div>
                <div>
                    <dt class="font-medium text-zinc-500">Action</dt>
                    <dd class="mt-1 text-zinc-950">{{ $auditLog->action }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-zinc-500">Target</dt>
                    <dd class="mt-1 text-zinc-950">{{ $auditLog->target_type }}</dd>
                    <dd class="text-xs text-zinc-500">ID {{ $auditLog->target_id }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-lg border border-zinc-200 bg-white p-5 lg:col-span-2">
            <h2 class="text-base font-semibold text-zinc-950">Safe Change Summary</h2>
            <pre class="mt-4 max-h-[32rem] overflow-auto rounded-md bg-zinc-950 p-4 text-xs leading-6 text-zinc-50">{{ json_encode($auditLog->changes ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </section>
    </div>
</x-layouts.app>

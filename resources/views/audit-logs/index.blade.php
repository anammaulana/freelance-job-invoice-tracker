<x-layouts.app title="Audit Logs">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Audit Logs</h1>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
        <table class="min-w-full divide-y divide-zinc-200 text-sm">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                <tr>
                    <th class="px-4 py-3">Time</th>
                    <th class="px-4 py-3">Actor</th>
                    <th class="px-4 py-3">Action</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3 text-right">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($auditLogs as $auditLog)
                    <tr>
                        <td class="px-4 py-3 text-zinc-700">{{ $auditLog->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-zinc-950">{{ $auditLog->actor_name ?? 'System' }}</div>
                            @if ($auditLog->actor_email)
                                <div class="text-xs text-zinc-500">{{ $auditLog->actor_email }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-700">{{ $auditLog->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-zinc-700">
                            {{ class_basename($auditLog->target_type) }} #{{ $auditLog->target_id }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('audit-logs.show', $auditLog) }}" class="font-medium text-zinc-900 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No audit logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $auditLogs->links() }}
    </div>
</x-layouts.app>

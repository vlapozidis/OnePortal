<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Admin Dashboard</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        @if (session('status'))
            <div class="rounded-none border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">Pending Requests</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">Approved Requests</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $approvedCount }}</p>
            </div>
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">Rejected Requests</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $rejectedCount }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-white">Pending Leave Requests</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($pendingRequests as $request)
                    <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                        <p class="text-base font-semibold text-white">{{ $request->user?->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-[#A1A1AA]">{{ $request->department?->name ?? 'No department' }}</p>
                        <p class="mt-2 text-sm text-[#A1A1AA]">
                            {{ optional($request->start_date)->format('Y-m-d') }} to {{ optional($request->end_date)->format('Y-m-d') }}
                        </p>
                        <p class="mt-2 text-sm text-white">{{ $request->reason }}</p>

                        <div class="mt-4 flex flex-col gap-2">
                            <form method="POST" action="{{ route('admin.leave-requests.approve', $request) }}" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_comment" placeholder="Audit note (optional)" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                                <button type="submit" class="shrink-0 rounded-none bg-green-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-600">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.leave-requests.reject', $request) }}" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_comment" placeholder="Audit note (optional)" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                                <button type="submit" class="shrink-0 rounded-none bg-red-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-600">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                        No pending leave requests.
                    </div>
                @endforelse
            </div>

            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
                {{ $pendingRequests->links() }}
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-white">Audit Trail (Recent Decisions)</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($recentAudits as $audit)
                    <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-base font-semibold text-white">{{ $audit->user?->name ?? 'Unknown' }}</p>
                            <span class="inline-flex shrink-0 rounded-none px-3 py-1 text-xs font-semibold {{ $audit->status === 'Approved' ? 'bg-green-900/40 text-green-300' : 'bg-red-900/40 text-red-300' }}">
                                {{ $audit->status }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-[#A1A1AA]">Reviewed by {{ $audit->reviewer?->name ?? '-' }}</p>
                        <p class="text-sm text-[#A1A1AA]">{{ optional($audit->reviewed_at)->format('Y-m-d H:i') }}</p>
                        <p class="mt-2 text-sm text-white">{{ $audit->admin_comment ?: 'No audit note' }}</p>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                        No audit history yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

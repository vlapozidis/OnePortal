<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">{{ __('Pending Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">{{ __('Approved Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $approvedCount }}</p>
            </div>
            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-4">
                <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">{{ __('Rejected Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $rejectedCount }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-white">{{ __('Pending Leave Requests') }}</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($pendingRequests as $request)
                    <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                        <p class="text-base font-semibold text-white">{{ $request->user?->name ?? __('Unknown') }}</p>
                        <p class="text-sm text-[#A1A1AA]">{{ $request->department?->name ?? __('No department') }}</p>
                        <p class="mt-2 text-sm text-[#A1A1AA]">
                            {{ optional($request->start_date)->format('Y-m-d') }} {{ __('to') }} {{ optional($request->end_date)->format('Y-m-d') }}
                        </p>
                        <p class="mt-2 text-sm text-white">{{ $request->reason }}</p>

                        <form
                            method="POST"
                            action="{{ route('admin.leave-requests.approve', $request) }}"
                            class="mt-4 flex flex-col gap-2"
                            onsubmit="return classterSetLeaveDecisionAction(this, '{{ route('admin.leave-requests.approve', $request) }}', '{{ route('admin.leave-requests.reject', $request) }}')"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="text" name="admin_comment" placeholder="{{ __('Audit note (optional)') }}" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                            <div class="flex gap-2">
                                <select name="decision" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white focus:border-[#DC2626] focus:ring-[#DC2626]">
                                    <option value="approve">{{ __('Approve') }}</option>
                                    <option value="reject">{{ __('Reject') }}</option>
                                </select>
                                <button type="submit" class="shrink-0 rounded-none bg-[#DC2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#B91C1C]">
                                    <i class="bi bi-send-check mr-1"></i>{{ __('Submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                        {{ __('No pending leave requests.') }}
                    </div>
                @endforelse
            </div>

            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
                {{ $pendingRequests->links() }}
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-sm font-semibold text-white">{{ __('Audit Trail (Recent Decisions)') }}</h3>

                <form
                    method="GET"
                    action="{{ route('admin.dashboard') }}"
                    id="audit-filters"
                    class="flex flex-wrap items-center gap-2"
                >
                    <select
                        id="audit_status"
                        name="audit_status"
                        onchange="document.getElementById('audit-filters').submit()"
                        class="w-36 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white focus:border-[#DC2626] focus:ring-[#DC2626]"
                    >
                        <option value="">{{ __('All Decisions') }}</option>
                        <option value="Approved" @selected($auditFilters['status'] === 'Approved')>{{ __('Approved') }}</option>
                        <option value="Rejected" @selected($auditFilters['status'] === 'Rejected')>{{ __('Rejected') }}</option>
                    </select>

                    <select
                        id="audit_department_id"
                        name="audit_department_id"
                        onchange="document.getElementById('audit-filters').submit()"
                        class="w-40 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-xs text-white focus:border-[#DC2626] focus:ring-[#DC2626]"
                    >
                        <option value="0">{{ __('All Departments') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected($auditFilters['department_id'] === $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($auditFilters['status'] !== '' || $auditFilters['department_id'] > 0)
                        <a href="{{ route('admin.dashboard') }}" class="text-xs text-[#A1A1AA] transition hover:text-white">
                            <i class="bi bi-x-circle mr-1"></i>{{ __('Clear filters') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($recentAudits as $audit)
                    <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-base font-semibold text-white">{{ $audit->user?->name ?? __('Unknown') }}</p>
                            <span class="inline-flex shrink-0 rounded-none px-3 py-1 text-xs font-semibold {{ $audit->status === 'Approved' ? 'bg-green-900/40 text-green-300' : 'bg-red-900/40 text-red-300' }}">
                                {{ __($audit->status) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-[#A1A1AA]">{{ __('Reviewed by :name', ['name' => $audit->reviewer?->name ?? '-']) }}</p>
                        <p class="text-sm text-[#A1A1AA]">{{ optional($audit->reviewed_at)->format('Y-m-d H:i') }}</p>
                        <p class="mt-2 text-sm text-white">{{ $audit->admin_comment ?: __('No audit note') }}</p>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                        {{ __('No audit history yet.') }}
                    </div>
                @endforelse
            </div>

            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
                {{ $recentAudits->links() }}
            </div>
        </div>
    </div>

    <script>
        function classterSetLeaveDecisionAction(form, approveUrl, rejectUrl) {
            form.action = form.decision.value === 'reject' ? rejectUrl : approveUrl;
            return true;
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4">
                <p class="text-xs uppercase tracking-wide text-[var(--portal-text-secondary)]">{{ __('Pending Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-[var(--portal-text-primary)]">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4">
                <p class="text-xs uppercase tracking-wide text-[var(--portal-text-secondary)]">{{ __('Approved Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-[var(--portal-text-primary)]">{{ $approvedCount }}</p>
            </div>
            <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4">
                <p class="text-xs uppercase tracking-wide text-[var(--portal-text-secondary)]">{{ __('Rejected Requests') }}</p>
                <p class="mt-2 text-2xl font-semibold text-[var(--portal-text-primary)]">{{ $rejectedCount }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Pending Leave Requests') }}</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($pendingRequests as $request)
                    <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                        <div class="flex items-center gap-3">
                            @if ($request->user)
                                <x-user-avatar :user="$request->user" size="sm" />
                            @endif
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $request->user?->name ?? __('Unknown') }}</p>
                                <p class="truncate text-sm text-[var(--portal-text-secondary)]">{{ $request->department?->name ?? __('No department') }}</p>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-[var(--portal-text-secondary)]">
                            {{ optional($request->start_date)->format('Y-m-d') }} {{ __('to') }} {{ optional($request->end_date)->format('Y-m-d') }}
                        </p>
                        <p class="mt-2 text-sm text-[var(--portal-text-primary)]">{{ $request->reason }}</p>

                        <form
                            method="POST"
                            action="{{ route('admin.leave-requests.approve', $request) }}"
                            class="mt-4 flex flex-col gap-2"
                            onsubmit="return onePortalSetLeaveDecisionAction(this, '{{ route('admin.leave-requests.approve', $request) }}', '{{ route('admin.leave-requests.reject', $request) }}')"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="text" name="admin_comment" placeholder="{{ __('Audit note (optional)') }}" class="w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] placeholder:text-[var(--portal-text-secondary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]">
                            <div class="flex gap-2">
                                <select name="decision" class="w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]">
                                    <option value="approve">{{ __('Approve') }}</option>
                                    <option value="reject">{{ __('Reject') }}</option>
                                </select>
                                <button type="submit" class="shrink-0 rounded-none bg-[var(--portal-primary)] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[var(--portal-primary-hover)]">
                                    <i class="bi bi-send-check mr-1"></i>{{ __('Submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                        {{ __('No pending leave requests.') }}
                    </div>
                @endforelse
            </div>

            <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
                {{ $pendingRequests->links() }}
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Audit Trail (Recent Decisions)') }}</h3>

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
                        class="w-36 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
                    >
                        <option value="">{{ __('All Decisions') }}</option>
                        <option value="Approved" @selected($auditFilters['status'] === 'Approved')>{{ __('Approved') }}</option>
                        <option value="Rejected" @selected($auditFilters['status'] === 'Rejected')>{{ __('Rejected') }}</option>
                    </select>

                    <select
                        id="audit_department_id"
                        name="audit_department_id"
                        onchange="document.getElementById('audit-filters').submit()"
                        class="w-40 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
                    >
                        <option value="0">{{ __('All Departments') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected($auditFilters['department_id'] === $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($auditFilters['status'] !== '' || $auditFilters['department_id'] > 0)
                        <a href="{{ route('admin.dashboard') }}" class="text-xs text-[var(--portal-text-secondary)] transition hover:text-[var(--portal-text-primary)]">
                            <i class="bi bi-x-circle mr-1"></i>{{ __('Clear filters') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($recentAudits as $audit)
                    <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex min-w-0 items-center gap-2">
                                @if ($audit->user)
                                    <x-user-avatar :user="$audit->user" size="xs" />
                                @endif
                                <p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $audit->user?->name ?? __('Unknown') }}</p>
                            </div>
                            <span class="inline-flex shrink-0 rounded-none px-3 py-1 text-xs font-semibold {{ $audit->status === 'Approved' ? 'bg-green-900/40 text-green-300' : 'bg-red-900/40 text-red-300' }}">
                                {{ __($audit->status) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-[var(--portal-text-secondary)]">{{ __('Reviewed by :name', ['name' => $audit->reviewer?->name ?? '-']) }}</p>
                        <p class="text-sm text-[var(--portal-text-secondary)]">{{ optional($audit->reviewed_at)->format('Y-m-d H:i') }}</p>
                        <p class="mt-2 text-sm text-[var(--portal-text-primary)]">{{ $audit->admin_comment ?: __('No audit note') }}</p>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                        {{ __('No audit history yet.') }}
                    </div>
                @endforelse
            </div>

            <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
                {{ $recentAudits->links() }}
            </div>
        </div>
    </div>

    <script>
        function onePortalSetLeaveDecisionAction(form, approveUrl, rejectUrl) {
            form.action = form.decision.value === 'reject' ? rejectUrl : approveUrl;
            return true;
        }
    </script>
</x-app-layout>

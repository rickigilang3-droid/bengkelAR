<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Notifikasi</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Notifikasi</h1>
                <p class="mt-1 text-sm text-slate-500">Pembaruan booking dan status servis Anda.</p>
            </div>
            @if ($customerNotifications->where('is_read', false)->isNotEmpty())
                <form method="POST" action="{{ route('customer.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:text-indigo-700">Tandai semua dibaca</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-5 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
        @endif
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            @if ($customerNotifications->isEmpty())
                <div class="px-6 py-14 text-center">
                    <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-indigo-700"><span class="material-symbols-outlined" aria-hidden="true">notifications_none</span></span>
                    <h2 class="mt-4 font-bold text-slate-900">Belum ada notifikasi</h2>
                    <p class="mt-1 text-sm text-slate-500">Pembaruan untuk akun dan servis Anda akan muncul di sini.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($customerNotifications as $notification)
                        <li class="{{ $notification->is_read ? 'bg-white' : 'bg-indigo-50/40' }}">
                            <a href="{{ route('customer.notifications.open', $notification) }}" class="flex gap-4 px-5 py-5 transition hover:bg-indigo-50 focus:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:px-7">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-indigo-100 text-indigo-700' }}">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">{{ $notification->type === 'booking_update' ? 'event_available' : 'build' }}</span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <h2 class="font-bold text-slate-900">{{ $notification->title }}</h2>
                                    <time datetime="{{ $notification->created_at->toIso8601String() }}" class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</time>
                                </div>
                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $notification->message }}</p>
                                @unless ($notification->is_read)<span class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700"><span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>Belum dibaca</span>@endunless
                                <span class="mt-3 flex items-center gap-1 text-xs font-bold text-indigo-700">Lihat detail <span aria-hidden="true">→</span></span>
                            </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t border-slate-100 px-5 py-4 sm:px-7">{{ $customerNotifications->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>

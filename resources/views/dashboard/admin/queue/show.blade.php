@extends('dashboard.admin.layouts.app')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.queue.index') }}" class="text-primary hover:text-primary-container font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali ke Antrean
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg border border-outline-variant p-lg">
                <h2 class="font-headline-md text-headline-md text-primary mb-lg">Detail Antrean #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }}</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="font-label-md text-secondary uppercase tracking-wider">Pelanggan</p>
                            <p class="font-body-md text-on-surface mt-2">{{ $queue->user->name }}</p>
                        </div>
                        <div>
                            <p class="font-label-md text-secondary uppercase tracking-wider">Kontak</p>
                            <p class="font-body-md text-on-surface mt-2">{{ $queue->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr class="border-outline-variant">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="font-label-md text-secondary uppercase tracking-wider">Kendaraan</p>
                            <p class="font-body-md text-on-surface mt-2">{{ $queue->vehicle->brand }} {{ $queue->vehicle->model }}</p>
                        </div>
                        <div>
                            <p class="font-label-md text-secondary uppercase tracking-wider">Plat Nomor</p>
                            <p class="font-body-md text-on-surface mt-2">{{ $queue->vehicle->plate_number }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="font-label-md text-secondary uppercase tracking-wider">Keluhan</p>
                        <p class="font-body-md text-on-surface mt-2">{{ $queue->complaint }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <form method="POST" action="{{ route('admin.queue.updateStatus', $queue->id) }}" class="bg-white rounded-lg border border-outline-variant p-lg">
                @csrf
                @method('PUT')

                <h3 class="font-headline-sm text-headline-sm text-primary mb-md">Update Status</h3>

                <div class="grid grid-cols-2 gap-4 mb-lg">
                    <div>
                        <label class="block font-semibold text-on-surface mb-2">Status <span class="text-error">*</span></label>
                        <select name="status" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('status') border-error @enderror" required>
                            <option value="waiting" @if($queue->status === 'waiting') selected @endif>Menunggu</option>
                            <option value="in_progress" @if($queue->status === 'in_progress') selected @endif>Sedang Dikerjakan</option>
                            <option value="completed" @if($queue->status === 'completed') selected @endif>Selesai</option>
                            <option value="cancelled" @if($queue->status === 'cancelled') selected @endif>Dibatalkan</option>
                        </select>
                        @error('status') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-on-surface mb-2">Prioritas</label>
                        <select name="priority" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="low" @if($queue->priority === 'low') selected @endif>Rendah</option>
                            <option value="medium" @if($queue->priority === 'medium') selected @endif>Sedang</option>
                            <option value="high" @if($queue->priority === 'high') selected @endif>Tinggi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-lg">
                    <label class="block font-semibold text-on-surface mb-2">Catatan Mekanik / Diagnosa Tambahan</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Tuliskan temuan atau instruksi mekanik...">{{ $queue->notes }}</textarea>
                </div>

                <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-container transition-colors">
                    Simpan Perubahan Status
                </button>
            </form>

            <!-- Ajukan Sparepart / Perbaikan Tambahan -->
            <div id="approvals" class="bg-white rounded-lg border border-outline-variant p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-md flex items-center gap-2">
                    <span class="material-symbols-outlined">rule</span> Ajukan Sparepart / Perbaikan Tambahan Ke Pelanggan
                </h3>
                <p class="text-xs text-secondary mb-md">Temuan kerusakan baru saat pembongkaran motor dapat dikirimkan ke HP/dashboard pelanggan untuk persetujuan biaya.</p>

                <form id="approval-form" onsubmit="submitApprovalItem(event)" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-slate-200 mb-lg">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Dari Inventaris Sparepart Bengkel</label>
                        <select onchange="selectInventoryPart(this)" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg bg-white font-semibold focus:ring-2 focus:ring-amber-500">
                            <option value="">-- Pilih Sparepart Stok Bengkel (Otomatis Isi Nama & Harga) --</option>
                            @foreach($inventories ?? [] as $part)
                            <option value="{{ $part->id }}" data-name="{{ $part->name }}" data-price="{{ $part->selling_price }}" data-notes="{{ $part->description }}">
                                [{{ $part->category }}] {{ $part->name }} — Rp {{ number_format($part->selling_price, 0, ',', '.') }} (Stok: {{ $part->stock }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Component / Sparepart</label>
                            <input type="text" id="app-item-name" required placeholder="Contoh: Kampas Rem Depan / Oli Gardan" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Biaya (Rp)</label>
                            <input type="number" id="app-item-cost" required placeholder="55000" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Diagnosa Mekanik</label>
                        <input type="text" id="app-item-notes" placeholder="Aus parah, berisiko mengikis piringan cakram" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg transition-colors flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">send</span> Kirim Permintaan Persetujuan
                    </button>
                </form>

                <!-- Daftar Item Persetujuan -->
                <h4 class="font-bold text-xs uppercase text-slate-500 tracking-wider mb-2">Riwayat Persetujuan Pelanggan</h4>
                <div class="space-y-2">
                    @forelse($queue->approvalItems as $appItem)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-white">
                        <div>
                            <h5 class="font-bold text-xs text-slate-800">{{ $appItem->item_name }}</h5>
                            <p class="text-[11px] text-slate-500">Rp {{ number_format($appItem->estimated_cost, 0, ',', '.') }} — {{ $appItem->notes }}</p>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full 
                            @if($appItem->status === 'approved') bg-emerald-100 text-emerald-700
                            @elseif($appItem->status === 'rejected') bg-rose-100 text-rose-700
                            @else bg-amber-100 text-amber-700 animate-pulse @endif">
                            {{ strtoupper($appItem->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Belum ada pengajuan sparepart tambahan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Panel Live Chat Admin - User -->
            <div id="chat" class="bg-white rounded-lg border border-outline-variant p-lg shadow-sm">
                <div class="flex items-center justify-between mb-md">
                    <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">chat</span> Obrolan Langsung dengan {{ $queue->user->name }}
                    </h3>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
                    </span>
                </div>

                <div id="admin-chat-container" class="h-64 overflow-y-auto p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3 mb-md">
                    @forelse($queue->chats as $c)
                    <div class="flex flex-col {{ $c->sender_type === 'admin' ? 'items-end' : 'items-start' }}">
                        <span class="text-[10px] text-slate-400 mb-0.5">{{ $c->sender->name ?? 'User' }}</span>
                        <div class="max-w-[80%] rounded-2xl px-3 py-2 text-xs shadow-sm {{ $c->sender_type === 'admin' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none' }}">
                            {{ $c->message }}
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-8">Belum ada percakapan. Mulai obrolan dengan pelanggan.</p>
                    @endforelse
                </div>

                <form onsubmit="sendAdminChat(event)" class="flex gap-2">
                    <input type="text" id="admin-chat-input" placeholder="Tulis pesan balasan ke pelanggan..." required class="flex-1 text-xs px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">send</span> Kirim
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Card -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg border border-outline-variant p-lg">
                <p class="font-label-md text-secondary uppercase tracking-wider mb-2">Status Saat Ini</p>
                <p class="inline-block px-4 py-2 rounded-full 
                    @if($queue->status === 'waiting') bg-blue-100 text-blue-700
                    @elseif($queue->status === 'in_progress') bg-yellow-100 text-yellow-700
                    @elseif($queue->status === 'completed') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700
                    @endif
                    font-semibold">{{ ucfirst(str_replace('_', ' ', $queue->status)) }}</p>
            </div>

            <div class="bg-white rounded-lg border border-outline-variant p-lg">
                <p class="font-label-md text-secondary uppercase tracking-wider mb-2">Prioritas</p>
                <p class="inline-block px-4 py-2 rounded-full 
                    @if($queue->priority === 'low') bg-green-100 text-green-700
                    @elseif($queue->priority === 'medium') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700
                    @endif
                    font-semibold">{{ ucfirst($queue->priority) }}</p>
            </div>

            <div class="bg-white rounded-lg border border-outline-variant p-lg">
                <p class="font-label-md text-secondary uppercase tracking-wider mb-2">Tanggal Masuk</p>
                <p class="font-body-md text-on-surface mt-2">{{ $queue->entered_at->format('d M Y') }}</p>
                <p class="font-body-md text-on-surface">{{ $queue->entered_at->format('H:i') }} WIB</p>
            </div>

            @if($queue->estimated_completion_at)
            <div class="bg-white rounded-lg border border-outline-variant p-lg">
                <p class="font-label-md text-secondary uppercase tracking-wider mb-2">Estimasi Selesai</p>
                <p class="font-body-md text-on-surface mt-2">{{ $queue->estimated_completion_at->format('d M Y') }}</p>
                <p class="font-body-md text-on-surface">{{ $queue->estimated_completion_at->format('H:i') }} WIB</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    const queueId = {{ $queue->id }};
    const csrfToken = '{{ csrf_token() }}';

    function escapeChatText(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    function selectInventoryPart(selectEl) {
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) return;

        document.getElementById('app-item-name').value = selectedOpt.getAttribute('data-name') || '';
        document.getElementById('app-item-cost').value = selectedOpt.getAttribute('data-price') || '';
        document.getElementById('app-item-notes').value = selectedOpt.getAttribute('data-notes') || 'Komponen terindikasi aus/perlu diganti';
    }

    function submitApprovalItem(e) {
        e.preventDefault();
        const itemName = document.getElementById('app-item-name').value;
        const itemCost = document.getElementById('app-item-cost').value;
        const itemNotes = document.getElementById('app-item-notes').value;

        fetch(`/api/approvals/${queueId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                item_name: itemName,
                estimated_cost: itemCost,
                notes: itemNotes
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Pengajuan pergantian sparepart berhasil dikirimkan ke pelanggan!');
                location.reload();
            } else {
                alert(data.error || 'Gagal mengirim pengajuan');
            }
        });
    }

    function loadAdminChats() {
        fetch(`/api/chats/${queueId}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('admin-chat-container');
                if (!container || !data.chats) return;

                if (data.chats.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-400 text-center py-8">Belum ada percakapan. Mulai obrolan dengan pelanggan.</p>`;
                    return;
                }

                let html = '';
                data.chats.forEach(c => {
                    const isMe = c.sender_type === 'admin';
                    html += `
                        <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} mb-2">
                            <span class="text-[10px] text-slate-400 mb-0.5">${escapeChatText(c.sender?.name || 'User')}</span>
                            <div class="max-w-[80%] rounded-2xl px-3 py-2 text-xs shadow-sm ${isMe ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none'}">
                                ${escapeChatText(c.message)}
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
                container.scrollTop = container.scrollHeight;
            });
    }

    function sendAdminChat(e) {
        e.preventDefault();
        const input = document.getElementById('admin-chat-input');
        const message = input.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('message', message);

        fetch(`/api/chats/${queueId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                loadAdminChats();
            }
        });
    }

    setInterval(loadAdminChats, 4000);
</script>
@endsection

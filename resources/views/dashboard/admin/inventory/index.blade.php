@extends('dashboard.admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-outline-variant shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Item Part</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalItems) }} <span class="text-xs font-normal text-slate-500">Jenis</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">inventory_2</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-outline-variant shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Stok Fisik</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStock) }} <span class="text-xs font-normal text-slate-500">Unit</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">package_2</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-outline-variant shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Menipis / Habis</p>
                <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ number_format($lowStockCount) }} <span class="text-xs font-normal text-rose-500">Peringatan</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">warning</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-outline-variant shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Estimasi Nilai Aset</p>
                <h3 class="text-2xl font-extrabold text-blue-700 mt-1">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">payments</span>
            </div>
        </div>
    </div>

    <!-- Actions & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-outline-variant shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sparepart, kode part, supplier..." class="w-full text-xs pl-9 pr-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="category" onchange="this.form.submit()" class="text-xs py-2 px-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">Semua Kategori</option>
                <option value="Oli & Cairan" {{ request('category') === 'Oli & Cairan' ? 'selected' : '' }}>Oli & Cairan</option>
                <option value="Suku Cadang CVT" {{ request('category') === 'Suku Cadang CVT' ? 'selected' : '' }}>Suku Cadang CVT</option>
                <option value="Sistem Pengereman" {{ request('category') === 'Sistem Pengereman' ? 'selected' : '' }}>Sistem Pengereman</option>
                <option value="Kelistrikan & Busi" {{ request('category') === 'Kelistrikan & Busi' ? 'selected' : '' }}>Kelistrikan & Busi</option>
                <option value="Filter & Udara" {{ request('category') === 'Filter & Udara' ? 'selected' : '' }}>Filter & Udara</option>
                <option value="Ban & Roda" {{ request('category') === 'Ban & Roda' ? 'selected' : '' }}>Ban & Roda</option>
            </select>
        </form>

        <button onclick="document.getElementById('add-part-modal').classList.remove('hidden')" class="w-full md:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center justify-center gap-2 transition-all">
            <span class="material-symbols-outlined text-[18px]">add_box</span> Tambah Sparepart Baru
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 font-bold rounded-xl text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span> {{ session('success') }}
    </div>
    @endif

    <!-- Inventory Data Table -->
    <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-sm text-slate-800">Daftar Stok Suku Cadang Bengkel</h3>
            <span class="text-xs text-slate-500">Menampilkan {{ $inventories->count() }} dari {{ $inventories->total() }} sparepart</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-4">Foto Produk</th>
                        <th class="p-4">Kode Part</th>
                        <th class="p-4">Nama Sparepart & Deskripsi</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4">Harga Beli</th>
                        <th class="p-4">Harga Jual</th>
                        <th class="p-4">Supplier</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($inventories as $inv)
                    <tr class="hover:bg-slate-50 transition-colors {{ $inv->stock <= $inv->min_stock ? 'bg-rose-50/50' : '' }}">
                        <td class="p-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                @if($inv->image_url)
                                    <img src="{{ $inv->image_url }}" alt="{{ $inv->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=400&q=80'">
                                @else
                                    <span class="material-symbols-outlined text-slate-400 text-xl">build_circle</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 font-mono font-bold text-slate-900">{{ $inv->part_code }}</td>
                        <td class="p-4 max-w-xs">
                            <span class="font-bold block text-slate-800 text-sm">{{ $inv->name }}</span>
                            <span class="text-[11px] text-slate-500 line-clamp-2">{{ $inv->description ?? 'Tidak ada deskripsi' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-700 font-semibold px-2.5 py-1 rounded-full border border-slate-200 text-[11px]">{{ $inv->category }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @if($inv->stock <= 0)
                                <span class="bg-rose-100 text-rose-700 font-extrabold px-3 py-1 rounded-full text-[11px]">HABIS (0)</span>
                            @elseif($inv->stock <= $inv->min_stock)
                                <span class="bg-amber-100 text-amber-800 font-extrabold px-3 py-1 rounded-full text-[11px] animate-pulse">MINIPIS ({{ $inv->stock }} {{ $inv->unit }})</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-800 font-extrabold px-3 py-1 rounded-full text-[11px]">{{ $inv->stock }} {{ $inv->unit }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-600 font-semibold">Rp {{ number_format($inv->purchase_price, 0, ',', '.') }}</td>
                        <td class="p-4 font-bold text-blue-700">Rp {{ number_format($inv->selling_price, 0, ',', '.') }}</td>
                        <td class="p-4 text-slate-500">{{ $inv->supplier ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editPart({{ json_encode($inv) }})" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg font-bold" title="Edit Stok & Harga">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.inventory.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Hapus sparepart ini dari inventaris?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg font-bold" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400 italic">Belum ada data sparepart di inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $inventories->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Sparepart Baru -->
<div id="add-part-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4 my-8">
        <div class="flex justify-between items-center border-b border-slate-200 pb-3">
            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">inventory_2</span> Tambah Sparepart Baru
            </h3>
            <button onclick="document.getElementById('add-part-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Part / SKU</label>
                    <input type="text" name="part_code" required placeholder="PRT-001" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Component</label>
                    <select name="category" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
                        <option value="Oli & Cairan">Oli & Cairan</option>
                        <option value="Suku Cadang CVT">Suku Cadang CVT</option>
                        <option value="Sistem Pengereman">Sistem Pengereman</option>
                        <option value="Kelistrikan & Busi">Kelistrikan & Busi</option>
                        <option value="Filter & Udara">Filter & Udara</option>
                        <option value="Ban & Roda">Ban & Roda</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sparepart Lengkap</label>
                <input type="text" name="name" required placeholder="Contoh: Kampas Rem Depan Vario 150 Original" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">URL Foto Produk</label>
                <input type="url" name="image_url" placeholder="https://images.unsplash.com/photo-..." class="w-full text-xs p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Spesifikasi Produk</label>
                <textarea name="description" rows="3" placeholder="Jelaskan spesifikasi, keunggulan, kompatibilitas motor..." class="w-full text-xs p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal</label>
                    <input type="number" name="stock" value="10" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Batas Min Stok</label>
                    <input type="number" name="min_stock" value="3" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan</label>
                    <input type="text" name="unit" value="Pcs" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Beli / Modal (Rp)</label>
                    <input type="number" name="purchase_price" required placeholder="45000" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual Bengkel (Rp)</label>
                    <input type="number" name="selling_price" required placeholder="65000" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Supplier / Distributor</label>
                <input type="text" name="supplier" placeholder="PT Astra Otoparts / Distributor Resmi" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200">
                <button type="button" onclick="document.getElementById('add-part-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md">Simpan Sparepart</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Sparepart -->
<div id="edit-part-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4 my-8">
        <div class="flex justify-between items-center border-b border-slate-200 pb-3">
            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">edit</span> Edit Sparepart & Stok
            </h3>
            <button onclick="document.getElementById('edit-part-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="edit-part-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Sparepart</label>
                    <input type="text" id="edit-name" name="name" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                    <select id="edit-category" name="category" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                        <option value="Oli & Cairan">Oli & Cairan</option>
                        <option value="Suku Cadang CVT">Suku Cadang CVT</option>
                        <option value="Sistem Pengereman">Sistem Pengereman</option>
                        <option value="Kelistrikan & Busi">Kelistrikan & Busi</option>
                        <option value="Filter & Udara">Filter & Udara</option>
                        <option value="Ban & Roda">Ban & Roda</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">URL Foto Produk</label>
                <input type="url" id="edit-image-url" name="image_url" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Spesifikasi Produk</label>
                <textarea id="edit-description" name="description" rows="3" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Saat Ini</label>
                    <input type="number" id="edit-stock" name="stock" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Min Stok Warning</label>
                    <input type="number" id="edit-min-stock" name="min_stock" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan</label>
                    <input type="text" id="edit-unit" name="unit" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Beli / Modal (Rp)</label>
                    <input type="number" id="edit-purchase-price" name="purchase_price" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" id="edit-selling-price" name="selling_price" required class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Supplier</label>
                <input type="text" id="edit-supplier" name="supplier" class="w-full text-xs p-2.5 border border-slate-300 rounded-xl">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200">
                <button type="button" onclick="document.getElementById('edit-part-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md">Update Sparepart</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPart(inv) {
        document.getElementById('edit-part-form').action = `/admin/inventory/${inv.id}`;
        document.getElementById('edit-name').value = inv.name;
        document.getElementById('edit-category').value = inv.category;
        document.getElementById('edit-image-url').value = inv.image_url || '';
        document.getElementById('edit-description').value = inv.description || '';
        document.getElementById('edit-stock').value = inv.stock;
        document.getElementById('edit-min-stock').value = inv.min_stock;
        document.getElementById('edit-unit').value = inv.unit;
        document.getElementById('edit-purchase-price').value = inv.purchase_price;
        document.getElementById('edit-selling-price').value = inv.selling_price;
        document.getElementById('edit-supplier').value = inv.supplier || '';
        document.getElementById('edit-part-modal').classList.remove('hidden');
    }
</script>
@endsection


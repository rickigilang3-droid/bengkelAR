<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nama lengkap" />
                <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Alamat email" />
                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="phone" value="Nomor telepon / WhatsApp" />
                <x-text-input id="phone" name="phone" type="tel" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('phone', $user->phone)" autocomplete="tel" inputmode="tel" maxlength="20" placeholder="Contoh: 0812 3456 7890" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            @php($regionProvinces = json_decode(file_get_contents(resource_path('js/data/indonesia-regions.json')), true, flags: JSON_THROW_ON_ERROR)['provinces'])

            <div>
                <x-input-label for="province" value="Provinsi" />
                <select id="province" name="province" autocomplete="address-level1" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih provinsi</option>
                    @foreach ($regionProvinces as $province)
                        <option value="{{ $province['name'] }}" @selected(old('province', $user->province) === $province['name'])>{{ $province['name'] }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('province')" />
            </div>

            <div>
                <x-input-label for="city" value="Kota/kabupaten" />
                <select id="city" name="city" autocomplete="address-level2" data-selected-city="{{ old('city', $user->city) }}" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled(! old('province', $user->province))>
                    <option value="">Pilih provinsi terlebih dahulu</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="address" value="Alamat lengkap" />
                <textarea id="address" name="address" rows="3" maxlength="1000" autocomplete="street-address" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, dan kecamatan" class="mt-1.5 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $user->address) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm font-semibold text-amber-900">Email Anda belum diverifikasi.</p>
                <p class="mt-1 text-sm leading-6 text-amber-800">Verifikasi email membantu menjaga keamanan dan memastikan Anda menerima pembaruan akun.</p>
                <button form="send-verification" class="mt-3 inline-flex min-h-9 items-center rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-sm font-bold text-amber-900 transition hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    Kirim ulang tautan verifikasi
                </button>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-semibold text-emerald-700">Tautan verifikasi baru telah dikirim.</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
            <x-primary-button class="min-h-11 rounded-xl bg-indigo-700 px-5 py-2.5 text-sm font-bold hover:bg-indigo-800 focus:ring-indigo-500">
                Simpan perubahan
            </x-primary-button>
            <p class="text-xs text-slate-500">Perubahan email memerlukan verifikasi ulang.</p>
        </div>
    </form>
</section>

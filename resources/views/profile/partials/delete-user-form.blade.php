<section class="space-y-4">
    <div class="max-w-2xl">
        <p class="text-sm leading-6 text-slate-600">Menghapus akun akan mengakhiri sesi Anda dan menghapus data akun sesuai kebijakan penyimpanan Bengkel AR. Pastikan Anda benar-benar ingin melanjutkan.</p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="min-h-10 rounded-xl px-4 py-2 text-sm font-bold"
    >Hapus akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-rose-100 text-rose-700">
                <span class="material-symbols-outlined" aria-hidden="true">warning</span>
            </span>
            <h2 class="mt-4 text-xl font-bold text-slate-900">Konfirmasi penghapusan akun</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Akun Anda akan dihapus secara permanen. Masukkan kata sandi untuk mengonfirmasi tindakan ini.</p>

            <div class="mt-6">
                <x-input-label for="password" value="Kata sandi saat ini" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="min-h-10 justify-center rounded-xl px-4 py-2">
                    Batal
                </x-secondary-button>
                <x-danger-button class="min-h-10 justify-center rounded-xl px-4 py-2">
                    Ya, hapus akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

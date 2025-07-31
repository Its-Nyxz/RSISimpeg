<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-700">Edit Username</h1>
        <a href="{{ route('userprofile.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="updateUsername">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <p class="text-success-700 font-medium" style="margin-bottom: 20px;">Silahkan Masukan Username Anda yang
                    Baru
                </p>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <p class="text-success-700 font-medium">Username Lama:</p>
                    <span class="font-bold text-center"
                        style="margin-right: 900px; color: #006633;">{{ $old_username ?? '-' }}</span>

                    <label for="username" class="text-sm font-medium text-success-700">Username Baru</label>
                    <input type="text" id="username" wire:model="username"
                        class="form-control @error('username') is-invalid @enderror w-full max-w-xs rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                </div>
                @error('username')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror

            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

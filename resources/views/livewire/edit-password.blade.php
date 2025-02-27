<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-700">Edit Password</h1>
        <a href="{{ route('userprofile.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="updatePassword">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <p class="text-green-700 font-medium mb-5">Silahkan Masukkan Password Anda yang Baru</p>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <!-- Password Lama -->
                    <label for="current_password" class="text-sm font-medium text-green-700">Password Lama</label>
                    <input type="password" id="current_password" wire:model="current_password" 
                        class="form-control @error('current_password') is-invalid @enderror w-full max-w-xs rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    @error('current_password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Password Baru -->
                    <label for="new_password" class="text-sm font-medium text-green-700">Password Baru</label>
                    <input type="password" id="new_password" wire:model="new_password" 
                        class="form-control @error('new_password') is-invalid @enderror w-full max-w-xs rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    @error('new_password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Konfirmasi Password Baru -->
                    <label for="new_password_confirmation" class="text-sm font-medium text-green-700">Ulangi Password Baru</label>
                    <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" 
                        class="form-control @error('new_password_confirmation') is-invalid @enderror w-full max-w-xs rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    @error('new_password_confirmation')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('error'))
        <div class="alert alert-danger mt-3 p-4 bg-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

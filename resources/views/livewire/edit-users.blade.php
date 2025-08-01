<div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Card 1 -->
    <x-card :title="'Data Users'">
        <form wire:submit.prevent="updateUser">
            <div class="grid grid-cols-2 gap-4">
                <!-- Nomor Induk Pegawai -->
                <div class="form-group col-span-2">
                    <label for="nip" class="text-sm font-medium text-success-700">Nomor Induk Pegawai</label>
                    <input type="text" id="nip" wire:model="nip"
                        class="form-control @error('nip') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-gray-200 text-gray-600 cursor-not-allowed  p-2.5"
                        readonly>
                    @error('nip')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group col-span-2">
                    <label for="email" class="text-sm font-medium text-success-700">Email</label>
                    <input type="email" id="email" wire:model="email"
                        class="form-control @error('email') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    @error('email')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group col-span-2">
                    <label for="username" class="text-sm font-medium text-success-700">Username</label>
                    <input type="text" id="username" wire:model="username"
                        class="form-control @error('username') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    @error('username')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="form-group col-span-2 flex justify-end">
                    <button type="submit"
                        class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </x-card>

    <!-- Card 2 -->
    <x-card :title="'Edit Password'">
        <form wire:submit.prevent="updatePassword">
            <div class="grid grid-cols-2 gap-4">
                <!-- Password Lama -->
                <div class="form-group col-span-2">
                    <label for="current_password" class="text-sm font-medium text-success-700">Password Lama</label>
                    <input type="password" id="current_password" wire:model="current_password"
                        class="form-control @error('current_password') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    @error('current_password')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div class="form-group col-span-2">
                    <label for="new_password" class="text-sm font-medium text-success-700">Password Baru</label>
                    <input type="password" id="new_password" wire:model="new_password"
                        class="form-control @error('new_password') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    @error('new_password')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-span-2">
                    <!-- Konfirmasi Password Baru -->
                    <label for="new_password_confirmation" class="text-sm font-medium text-success-700">Ulangi Password
                        Baru</label>
                    <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                        class="form-control @error('new_password_confirmation') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    @error('new_password_confirmation')
                        <span class="text-danger-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="form-group col-span-2 flex justify-end">
                    <button type="submit"
                        class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </x-card>
    <!-- Notifikasi -->
    @if (session()->has('error'))
        <div class="alert alert-danger mt-3 p-4 bg-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

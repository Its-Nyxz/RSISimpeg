<div>
    <form wire:submit.prevent="updateJadwal">
        <!-- Form Edit Jadwal -->
        <div class="grid grid-cols-1 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <!-- Nama User -->
            <div class="form-group">
                <label for="user_id" class="block text-sm font-medium text-green-900">Nama User</label>
                <select id="user_id" wire:model="user_id" class="form-control @error('user_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Shift -->
            <div class="form-group">
                <label for="shift_id" class="block text-sm font-medium text-green-900">Shift</label>
                <select id="shift_id" wire:model="shift_id" class="form-control @error('shift_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Shift</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                    @endforeach
                </select>
                @error('shift_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Opsi Absensi -->
            <div class="form-group">
                <label for="opsi_id" class="block text-sm font-medium text-green-900">Opsi Absensi</label>
                <select id="opsi_id" wire:model="opsi_id" class="form-control @error('opsi_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Opsi</option>
                    @foreach($opsiAbsens as $opsi)
                        <option value="{{ $opsi->id }}">{{ $opsi->name }}</option>
                    @endforeach
                </select>
                @error('opsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Jadwal -->
            <div class="form-group">
                <label for="tanggal_jadwal" class="block text-sm font-medium text-green-900">Tanggal Jadwal</label>
                <input type="date" id="tanggal_jadwal" wire:model="tanggal_jadwal" class="form-control @error('tanggal_jadwal') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('tanggal_jadwal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Keterangan -->
            <div class="form-group">
                <label for="keterangan_absen" class="block text-sm font-medium text-green-900">Keterangan Absensi</label>
                <select id="keterangan_absen" wire:model="keterangan_absen" class="form-control @error('keterangan_absen') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Keterangan Absensi</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Libur">Libur</option>
                    <option value="Tugas">Tugas</option>
                    <option value="Ijin">Ijin</option>
                    <option value="Sakit">Sakit</option>
                </select>
                @error('keterangan_absen')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

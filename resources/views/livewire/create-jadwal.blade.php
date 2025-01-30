<div>
<div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Jadwal</h1>   
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Nama (User) -->
            <div class="form-group col-span-2">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama</label>
                <select id="nama" wire:model="nama" class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Nama</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Shift -->
            <div class="form-group col-span-2">
                <label for="shift" class="block text-sm font-medium text-green-900">Shift</label>
                <select id="shift" wire:model="shift_id" class="form-control @error('shift_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
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
            <div class="form-group col-span-2">
                <label for="opsi" class="block text-sm font-medium text-green-900">Opsi Absensi</label>
                <select id="opsi" wire:model="opsi_id" class="form-control @error('opsi_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Opsi Absensi</option>
                    @foreach($opsis as $opsi)
                        <option value="{{ $opsi->id }}">{{ $opsi->name }}</option>
                    @endforeach
                </select>
                @error('opsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Jadwal -->
            <div class="form-group col-span-2">
                <label for="tanggal" class="block text-sm font-medium text-green-900">Tanggal Jadwal</label>
                <input type="date" id="tanggal" wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('tanggal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keterangan Absensi -->
            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan Absensi</label>
                <select id="keterangan" wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Keterangan Absensi</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Libur">Libur</option>
                    <option value="Tugas">Tugas</option>
                    <option value="Ijin">Ijin</option>
                    <option value="Sakit">Sakit</option>
                </select>
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
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

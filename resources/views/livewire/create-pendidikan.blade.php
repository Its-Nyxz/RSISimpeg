<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Pendidikan</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <!-- Card Container -->
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">

            <!-- Nama Pendidikan -->
            <div class="form-group mb-4">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama Pendidikan</label>
                <input type="text" id="nama" wire:model="nama" class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" required>
                @error('nama') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi Pendidikan -->
            <div class="form-group mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-green-900">Deskripsi Pendidikan</label>
                <textarea id="deskripsi" wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('deskripsi') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Golongan Minimal -->
            <div class="form-group mb-4">
                <label for="minim_gol" class="block text-sm font-medium text-green-900">Golongan Minimal</label>
                <select id="minim_gol" wire:model="minim_gol" class="form-control @error('minim_gol') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" required>
                    <option value="">Pilih Golongan Minimal</option>
                    @foreach ($golongans as $golongan)
                        <option value="{{ $golongan->id }}">{{ $golongan->nama }}</option>
                    @endforeach
                </select>
                @error('minim_gol') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Golongan Maksimal -->
            <div class="form-group mb-4">
                <label for="maxim_gol" class="block text-sm font-medium text-green-900">Golongan Maksimal</label>
                <select id="maxim_gol" wire:model="maxim_gol" class="form-control @error('maxim_gol') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" required>
                    <option value="">Pilih Golongan Maksimal</option>
                    @foreach ($golongans as $golongan)
                        <option value="{{ $golongan->id }}">{{ $golongan->nama }}</option>
                    @endforeach
                </select>
                @error('maxim_gol') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Tombol Aksi -->
            <div class="form-group flex justify-end space-x-2">
                <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Pendidikan
                </button>
            </div>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>

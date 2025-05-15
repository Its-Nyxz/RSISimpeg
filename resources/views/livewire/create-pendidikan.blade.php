<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Pendidikan</h1>
        <a href="{{ route('pendidikan.index') }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group mb-4">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama Pendidikan</label>
                <input type="text" id="nama" wire:model="nama" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" required>
                @error('nama') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-green-900">Deskripsi Pendidikan</label>
                <textarea id="deskripsi" wire:model="deskripsi" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('deskripsi') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Golongan Minimal -->
            <div class="form-group mb-4 relative">
                <label for="minim_gol" class="block text-sm font-medium text-green-900">Golongan Minimal</label>
                <input type="text" id="minim_gol" wire:model="minim_gol_nama" 
                    wire:focus="fetchSuggestions('minim_gol', $event.target.value)" 
                    wire:input="fetchSuggestions('minim_gol', $event.target.value)"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Golongan Minimal..." autocomplete="off">

                <!-- Dropdown -->
                @if (!empty($minimGolonganOptions))
                    <ul class="absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 z-50 max-h-40 overflow-y-auto">
                        @foreach($minimGolonganOptions as $golongan)
                            <li wire:click="selectGolongan('minim_gol', '{{ $golongan->id }}', '{{ $golongan->nama }}')" class="p-2 cursor-pointer hover:bg-green-200">
                                {{ $golongan->nama }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('minim_gol') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Golongan Maksimal -->
            <div class="form-group mb-4 relative">
                <label for="maxim_gol" class="block text-sm font-medium text-green-900">Golongan Maksimal</label>
                <input type="text" id="maxim_gol" wire:model="maxim_gol_nama" 
                    wire:focus="fetchSuggestions('maxim_gol', $event.target.value)" 
                    wire:input="fetchSuggestions('maxim_gol', $event.target.value)"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Golongan Maksimal..." autocomplete="off">

                <!-- Dropdown -->
                @if (!empty($maximGolonganOptions))
                    <ul class="absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 z-50 max-h-40 overflow-y-auto">
                        @foreach($maximGolonganOptions as $golongan)
                            <li wire:click="selectGolongan('maxim_gol', '{{ $golongan->id }}', '{{ $golongan->nama }}')" class="p-2 cursor-pointer hover:bg-green-200">
                                {{ $golongan->nama }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('maxim_gol') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group flex justify-end space-x-2">
                <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Pendidikan
                </button>
            </div>
        </div>
    </form>

    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

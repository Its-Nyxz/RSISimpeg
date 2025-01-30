<div>
<div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Level Unit</h1>   
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <div class="form-group col-span-2">
                <label for="unitkerja" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <select id="unitkerja" wire:model="unit_id" class="form-control @error('unit_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Nama Unit</option>
                    @foreach($unitkerja as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>                
                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label for="levelpoint" class="block text-sm font-medium text-green-900">Level Point</label>
                <select id="levelpoint" wire:model="level_id" class="form-control @error('level_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Level Point</option>
                    @foreach($levelpoint as $item) <!-- Menggunakan $levelpoints -->
                        <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->point }}</option>
                    @endforeach
                </select>                
                @error('level_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
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

<div>
   
    <form wire:submit.prevent="updateProfile">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Nama -->
            <div class="form-group col-span-2">
                <label for="name" class="text-sm font-medium text-green-700">Nama</label>
                <input type="text" id="name" wire:model="name" 
                    class="form-control @error('name') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('name')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jabatan -->
            <div class="form-group col-span-2">
                <label for="jabatan_id" class="text-sm font-medium text-green-700">Jabatan</label>
                <select id="jabatan_id" wire:model="jabatan_id"
                    class="form-control @error('jabatan_id') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                    @endforeach
                </select>
                @error('jabatan_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tempat -->
            <div class="form-group">
                <label for="tempat" class="text-sm font-medium text-green-700">Tempat</label>
                <input type="text" id="tempat" wire:model="tempat" 
                    class="form-control @error('tempat') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('tempat')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div class="form-group">
                <label for="tanggal_lahir" class="text-sm font-medium text-green-700">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" wire:model="tanggal_lahir" 
                    class="form-control @error('tanggal_lahir') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('tanggal_lahir')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Tetap -->
            <div class="form-group  col-span-2  ">
                <label for="tanggal_tetap" class="text-sm font-medium text-green-700">Tanggal Tetap</label>
                <input type="date" id="tanggal_tetap" wire:model="tanggal_tetap" 
                    class="form-control @error('tanggal_tetap') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('tanggal_tetap')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pendidikan Awal -->
            <div class="form-group">
                <label for="pendidikan_id" class="text-sm font-medium text-green-700">Pendidikan Awal</label>
                <select id="pendidikan_id" wire:model="pendidikan_id"
                    class="form-control @error('pendidikan_id') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($pendidikans as $pendidikan)
                        <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                    @endforeach
                </select>
                @error('pendidikan_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pendidikan Penyesuaian -->
            <div class="form-group">
                <label for="pendidikan_penyesuaian" class="text-sm font-medium text-green-700">Pendidikan Penyesuaian</label>
                <input type="text" id="pendidikan_penyesuaian" wire:model="pendidikan_penyesuaian" 
                    class="form-control @error('pendidikan_penyesuaian') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('pendidikan_penyesuaian')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Penyesuaian -->
            <div class="form-group col-span-2">
                <label for="tgl_penyesuaian" class="text-sm font-medium text-green-700">Tanggal Penyesuaian</label>
                <input type="date" id="tgl_penyesuaian" wire:model="tgl_penyesuaian" 
                    class="form-control @error('tgl_penyesuaian') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('tgl_penyesuaian')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Pensiun -->
            <div class="form-group col-span-2">
                <label for="pensiun" class="text-sm font-medium text-green-700">Informasi Pensiun</label>
                <input type="date" id="pensiun" wire:model="pensiun" 
                    class="form-control @error('pensiun') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('pensiun')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group flex justify-end mt-4">
            <button type="submit" class="flex mb-4 items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2 "></i> Simpan
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
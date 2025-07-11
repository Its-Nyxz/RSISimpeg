<div>

    <form wire:submit.prevent="updateProfile">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            {{-- Nama --}}
            <div class="form-group col-span-2">
                <label for="name" class="text-sm font-medium text-success-700">Nama</label>
                <input type="text" id="name" wire:model="name"
                    class="form-control @error('name') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('name')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- NIP --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="nip" class="text-sm font-medium text-success-700">NIP (Nomor Induk Pegawai)</label>
                <input type="text" id="nip" wire:model="nip"
                    class="form-control @error('nip') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nip')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- No KTP --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="no_ktp" class="text-sm font-medium text-success-700">No KTP</label>
                <input type="text" id="no_ktp" wire:model="no_ktp"
                    class="form-control @error('no_ktp') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('no_ktp')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- No Hp --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="no_hp" class="text-sm font-medium text-success-700">No. Hp</label>
                <input type="text" id="no_hp" wire:model="no_hp"
                    class="form-control @error('no_hp') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('no_hp')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- No Rekening --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="no_rek" class="text-sm font-medium text-success-700">No Rekening</label>
                <input type="text" id="no_rek" wire:model="no_rek"
                    class="form-control @error('no_rek') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('no_rek')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tempat Lahir --}}
            <div class="form-group">
                <label for="tempat_lahir" class="text-sm font-medium text-success-700">Tempat Lahir</label>
                <input type="text" id="tempat_lahir" wire:model="tempat_lahir"
                    class="form-control @error('tempat_lahir') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('tempat_lahir')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div class="form-group">
                <label for="tanggal_lahir" class="text-sm font-medium text-success-700">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" wire:model="tanggal_lahir"
                    class="form-control @error('tanggal_lahir') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('tanggal_lahir')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kategori Pendidikan --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="kategori_pendidikan" class="text-sm font-medium text-success-700">Kategori
                    Pendidikan</label>
                <select id="kategori_pendidikan" wire:model="kategori_pendidikan"
                    class="form-control @error('kategori_pendidikan') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach ($pendidikans as $pendidikan)
                        <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                    @endforeach
                </select>
                @error('pendidikan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Pendidikan --}}
            <div class="form-group col-span-2 md:col-span-1">
                <label for="pendidikan" class="text-sm font-medium text-success-700">Pendidikan</label>
                <input type="text" id="pendidikan" wire:model="pendidikan"
                    class="form-control @error('pendidikan') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('pendidikan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Institusi --}}
            <div class="form-group col-span-2">
                <label for="institusi" class="text-sm font-medium text-success-700">Institusi</label>
                <input type="text" id="institusi" wire:model="institusi"
                    class="form-control @error('institusi') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('institusi')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="form-group col-span-2">
                <label for="jenisKelamin" class="text-sm font-medium text-success-700">Jenis Kelamin</label>
                <label class="flex items-center gap-4">
                    <input type="radio" name="jenisKelamin" id="laki" wire:model.live="jenisKelamin"
                        value="1"
                        class="form-radio h-4 w-4 text-success-600 border-gray-300 focus:ring-success-500">
                    <span class="ml-1 text-gray-900">Laki-laki</span>
                    <input type="radio" name="jenisKelamin" id="perempuan" wire:model.live="jenisKelamin"
                        value="0"
                        class="form-radio h-4 w-4 text-success-600 border-gray-300 focus:ring-success-500">
                    <span class="ml-1 text-gray-900">Perempuan</span>
                </label>
            </div>

            {{-- Alamat --}}
            <div class="form-group col-span-2">
                <label for="alamat" class="text-sm font-medium text-success-700">Alamat</label>
                <textarea id="alamat" wire:model.live="alamat"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5 "
                    placeholder="Masukkan alamat" rows="3"></textarea>
                @error('alamat')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-span-2">
                <label for="photo" class="text-sm font-medium text-success-700">Foto Profil</label>
                <input type="file" id="photo" wire:model="photo"
                    class="form-control @error('photo') is-invalid @enderror w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('photo')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror

                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                @elseif ($currentPhoto)
                    <img src="{{ asset('storage/photos/' . $currentPhoto) }}"
                        class="mt-2 w-32 h-32 object-cover rounded-lg">
                @endif
            </div>
        </div>

        {{-- button sumbit --}}
        <div class="form-group flex justify-end mt-4 mb-4">
            <button type="submit"
                class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    {{-- Notifikasi --}}
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

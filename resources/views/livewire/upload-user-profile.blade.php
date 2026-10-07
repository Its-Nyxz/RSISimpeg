<div class="p-4 space-y-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-700">Upload Dokumen</h1>
        <a href="{{ route('userprofile.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="space-y-4">
        <div class="flex flex-col gap-2">
            <label class="font-medium text-gray-700">Jenis Dokumen</label>
            <select wire:model.live="jenis_file_id" class="border rounded p-2">
                <option value="">-- Pilih Dokumen --</option>
                @foreach ($jenisFiles as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                @endforeach
            </select>
            @error('jenis_file_id')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <label class="font-medium text-gray-700">Upload File</label>
            <input type="file" wire:model.live="file" class="border rounded p-2" />
            <p class="text-xs text-gray-500">Maksimal ukuran file: 2 MB</p>
            @error('file')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        @if ($isSip)
            <div class="flex flex-col gap-2">
                <label class="font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" wire:model.live="mulai" class="border rounded p-2" />
                @error('mulai')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" wire:model.live="selesai" class="border rounded p-2" />
                @error('selesai')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @elseif ($pelatihan)
            <div class="flex flex-col gap-2">
                <label class="font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" wire:model.live="mulai" class="border rounded p-2" />
                @error('mulai')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" wire:model.live="selesai" class="border rounded p-2" />
                @error('selesai')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-medium text-gray-700">Jumlah Jam</label>
                <input type="number" wire:model.live="jumlah_jam" class="border rounded p-2" />
                @error('jumlah_jam')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <button wire:click="save" wire:loading.attr="disabled" wire:target="save,file"
            class="bg-success-600 text-white px-4 py-2 rounded hover:bg-success-700 transition mt-4">
            <span wire:loading.remove wire:target="save">Upload</span>
            <span wire:loading wire:target="save">Mengupload...</span>
        </button>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-bold">Daftar Dokumen Saya</h2>
        <div class="mt-4 space-y-2">
            @forelse ($uploadedFiles as $file)
                <div class="flex justify-between items-center p-3 border rounded bg-white shadow-sm">
                    <div>
                        <p><strong>{{ $file->jenisFile->name ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700">{{ $file->name }}</p>
                        @if ($file->mulai && $file->selesai && !str_contains(strtolower($file->jenisFile->name ?? ''), 'str'))
                            <p class="text-xs text-gray-600">Berlaku: {{ $file->mulai }} s/d {{ $file->selesai }}</p>
                        @endif
                        @if ($file->jumlah_jam)
                            <p class="text-xs text-gray-600">Jumlah Jam: {{ $file->jumlah_jam }} jam</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('userprofile.download', $file->id) }}"
                            class="text-success-700 hover:underline text-sm font-medium">
                            Download
                        </a>
                        <button type="button"
                            onclick="confirmAlert('Apakah Anda yakin ingin menghapus dokumen {{ $file->name }}?', 'Ya, hapus!', () => @this.call('delete', {{ $file->id }}))"
                            class="text-red-600 hover:underline text-sm font-medium">
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-700">Belum ada dokumen yang diupload.</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            const bindAlert = () => {
                if (window._uploadSwalInit) return;
                window._uploadSwalInit = true;

                Livewire.on('swal:alert', (data) => {
                    const event = Array.isArray(data) ? data[0] : (data || {});
                    const title = event.title || (event.icon === 'success' ? 'Berhasil' : 'Gagal');
                    const message = event.text || event.message || '';
                    const icon = event.icon || 'info';

                    if (typeof feedback === 'function') {
                        feedback(title, message, icon);
                    } else if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: title,
                            html: message,
                            icon: icon,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }
                });
            };

            if (typeof Livewire !== 'undefined') {
                bindAlert();
            }
            document.addEventListener('livewire:initialized', bindAlert);
            document.addEventListener('livewire:init', bindAlert);
        })();
    </script>
@endpush

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
                        <button type="button"
                            wire:click="setPreviewDokumen({{ $file->id }})"
                            class="text-blue-600 hover:underline text-sm font-medium">
                            Preview
                        </button>
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

    {{-- Modal Preview Dokumen --}}
    <x-modal name="modal-preview-dokumen" maxWidth="2xl" :show="false">
        <div class="flex flex-col max-h-[90vh] font-sans">
            {{-- Header Modal --}}
            <div class="px-5 py-3.5 bg-success-400 text-success-950 flex justify-between items-center rounded-t-lg flex-shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-lg text-success-950"></i>
                    <h3 class="font-extrabold text-sm sm:text-base uppercase tracking-tight text-success-950">Pratinjau Dokumen</h3>
                </div>
                <button type="button" x-on:click="$dispatch('close-modal', 'modal-preview-dokumen')"
                    class="text-success-950 hover:text-red-700 text-xl font-bold p-1 leading-none transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Box Info Nama Dokumen --}}
            <div class="bg-success-50 p-4 border-b border-success-200 flex-shrink-0">
                <span class="block text-[10px] font-bold text-success-800 uppercase tracking-wider">Nama File / Dokumen</span>
                <h4 class="text-base font-extrabold text-gray-900 leading-tight mt-0.5 break-all">{{ $previewName ?? 'Dokumen' }}</h4>
            </div>

            {{-- Konten Dokumen --}}
            <div class="p-4 overflow-y-auto flex-1 flex items-center justify-center bg-gray-100 min-h-[350px]">
                @if ($previewUrl)
                    @if (in_array($previewExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                        <img src="{{ $previewUrl }}" alt="{{ $previewName }}" class="max-h-[65vh] max-w-full rounded-lg shadow-md object-contain border border-gray-200" />
                    @elseif ($previewExtension === 'pdf')
                        <iframe src="{{ $previewUrl }}" class="w-full h-[65vh] rounded-lg border border-gray-300 bg-white"></iframe>
                    @else
                        <div class="text-center p-6 space-y-3">
                            <i class="fa-solid fa-file-arrow-down text-4xl text-gray-400"></i>
                            <p class="text-sm text-gray-700 font-medium">Dokumen (.{{ $previewExtension }}) tidak dapat ditampilkan langsung.</p>
                            <a href="{{ $previewUrl }}" download="{{ $previewName }}" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-success-600 text-white rounded-lg text-xs font-bold hover:bg-success-700 transition">
                                <i class="fa-solid fa-download"></i> Unduh File
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-10 text-gray-500 font-medium text-sm">
                        Memuat pratinjau dokumen...
                    </div>
                @endif
            </div>

            {{-- Footer Modal --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-lg flex-shrink-0">
                <button type="button" x-on:click="$dispatch('close-modal', 'modal-preview-dokumen')"
                    class="px-5 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </x-modal>
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

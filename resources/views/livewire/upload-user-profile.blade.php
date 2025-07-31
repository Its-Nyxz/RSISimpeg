<div class="p-4 space-y-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-700">Upload Dokumen</h1>
        <a href="{{ route('userprofile.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if (session()->has('success'))
        <div class="p-2 bg-success-200 text-success-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-2 bg-red-200 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col gap-2">
            <label>Jenis Dokumen</label>
            <select wire:model.live="jenis_file_id" class="border rounded p-2">
                <option value="">-- Pilih Dokumen --</option>
                @foreach ($jenisFiles as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-2">
            <label>Upload File</label>
            <input type="file" wire:model.live="file" class="border rounded p-2" />
        </div>

        <!-- Muncul input date kalau SIP atau STR -->
        @if ($isSipStr)
            <div class="flex flex-col gap-2">
                <label>Tanggal Mulai</label>
                <input type="date" wire:model.live="mulai" class="border rounded p-2" />

                <label>Tanggal Selesai</label>
                <input type="date" wire:model.live="selesai" class="border rounded p-2" />
            </div>
        @elseif($pelatihan)
            <div class="flex flex-col gap-2">
                <label>Tanggal Mulai</label>
                <input type="date" wire:model.live="mulai" class="border rounded p-2" />

                <label>Tanggal Selesai</label>
                <input type="date" wire:model.live="selesai" class="border rounded p-2" />

                <label>Jumlah Jam</label>
                <input type="number" wire:model.live="jumlah_jam" class="border rounded p-2" />
            </div>
        @endif

        <button wire:click="save"
            class="bg-success-600 text-white px-4 py-2 rounded hover:bg-success-700 transition mt-4">
            Upload
        </button>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-bold">Daftar Dokumen Saya</h2>
        <div class="mt-4 space-y-2">
            @forelse ($uploadedFiles as $file)
                <div class="flex justify-between items-center p-2 border rounded bg-white">
                    <div>
                        <p><strong>{{ $file->jenisFile->name ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-700">{{ $file->name }}</p>
                        @if ($file->mulai && $file->selesai)
                            <p class="text-xs text-gray-600">Berlaku: {{ $file->mulai }} s/d {{ $file->selesai }}</p>
                        @endif
                        @if ($file->jumlah_jam)
                            <p class="text-xs text-gray-600">Jumlah Jam: {{ $file->jumlah_jam }} jam</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                            class="text-success-700 hover:underline text-sm">Download</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-700">Belum ada dokumen yang diupload.</p>
            @endforelse
        </div>
    </div>
</div>

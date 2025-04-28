<div class="p-4 space-y-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-700">Upload Dokumen</h1>
        <a href="{{ route('userprofile.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if (session()->has('success'))
        <div class="p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex flex-col gap-2">
            <label>Jenis Dokumen</label>
            <select wire:model="jenis_file_id" class="border rounded p-2">
                <option value="">-- Pilih Dokumen --</option>
                @foreach ($jenisFiles as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-2">
            <label>Upload File</label>
            <input type="file" wire:model="file" class="border rounded p-2" />
        </div>

        <!-- Muncul input date kalau SIP atau STR -->
        @if ($isSipStr)
            <div class="flex flex-col gap-2">
                <label>Tanggal Mulai</label>
                <input type="date" wire:model="mulai" class="border rounded p-2" />

                <label>Tanggal Selesai</label>
                <input type="date" wire:model="selesai" class="border rounded p-2" />
            </div>
        @endif

        <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mt-4">
            Upload
        </button>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-bold">Daftar Dokumen Saya</h2>
        <div class="mt-4 space-y-2">
            @forelse ($uploadedFiles as $file)
                <div class="flex justify-between items-center p-2 border rounded">
                    <div>
                        <p><strong>{{ $file->jenisFile->name ?? '-' }}</strong></p>
                        <p class="text-sm text-gray-500">{{ $file->name }}</p>
                        @if ($file->mulai && $file->selesai)
                            <p class="text-xs text-gray-400">Berlaku: {{ $file->mulai }} s/d {{ $file->selesai }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                            class="text-green-600 hover:underline text-sm">Download</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada dokumen yang diupload.</p>
            @endforelse
        </div>
    </div>
</div>

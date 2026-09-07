<div class="mt-6">

    <h2 class="text-xl font-bold">
        Daftar Dokumen Saya
    </h2>


    <div class="mt-4 space-y-2">

        @forelse ($uploadedFiles as $file)

            <div class="flex justify-between items-center p-2 border rounded bg-white">

                <div>

                    <p>
                        <strong>
                            {{ $file->jenisFile->name ?? '-' }}
                        </strong>
                    </p>

                    <p class="text-sm text-gray-700">
                        {{ $file->name }}
                    </p>


                    @if ($file->mulai && $file->selesai)

                        <p class="text-xs text-gray-600">
                            Berlaku:
                            {{ $file->mulai }}
                            s/d
                            {{ $file->selesai }}
                        </p>

                    @endif


                    @if ($file->jumlah_jam)

                        <p class="text-xs text-gray-600">
                            Jumlah Jam:
                            {{ $file->jumlah_jam }}
                            jam
                        </p>

                    @endif

                </div>


                <div class="flex gap-2">

                    <a href="{{ asset('storage/' . $file->path) }}"
                        target="_blank"
                        class="text-success-700 hover:underline text-sm">

                        Download

                    </a>


                    <button type="button"
                        onclick="confirmRemove(
                            'Apakah kamu yakin ingin menghapus dokumen ini?',
                            () => {
                                $wire.deleteFile({{ $file->id }})
                            }
                        )"
                        class="text-red-600 hover:underline text-sm">

                        Hapus

                    </button>

                </div>

            </div>


        @empty

            <p class="text-gray-700">
                Belum ada dokumen yang diupload.
            </p>

        @endforelse

    </div>

</div>
<x-body>
    {{-- @if ($jadwal_id) --}}
    {{-- Jika jadwal tersedia, tampilkan komponen timer --}}
    <livewire:timer :jadwal_id="$jadwal_id" />
    {{-- @else --}}
    {{-- Jika tidak ada jadwal, tampilkan pesan dengan Flowbite --}}
    {{-- <div class="flex items-center p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50"
            role="alert">
            <svg class="flex-shrink-0 w-6 h-6 text-yellow-800" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 20h.01M20.2 7c.7 1.6 1.8 2.4 1.8 4.5 0 3-1 4.5-3 5-1.5.4-2.5.5-4 1.5m-4 0c-1.5-1-2.5-1.1-4-1.5-2-.5-3-2-3-5 0-2.1 1.1-2.9 1.8-4.5" />
            </svg>
            <div class="ml-3 text-sm font-medium">
                Tidak ada jadwal yang tersedia untuk hari ini. Silakan hubungi atasan atau admin jika ada kesalahan.
            </div>
        </div>
    @endif --}}
</x-body>

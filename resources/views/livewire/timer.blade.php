<div>
    <h1 class="text-2xl font-bold text-success-900">Timer</h1>

    <div class="p-4 text-left">
        {{-- Tanggal --}}
        <p class="text-gray-500">{{ now()->format('l, d F Y') }}</p>

        {{-- Waktu Timer --}}
        <h1 id="timerDisplay" class="text-8xl font-bold text-center ">
            {{-- {{ gmdate('H:i:s', abs($unixtimer)) }} --}}
            00:00:00
        </h1>
        <div class="p-10 text-center">
            {{-- Tombol Mulai --}}
            <button id="startButton" wire:click="$set('showStartModal', true)"
                class="px-6 py-2 font-bold rounded 
                {{ $timeOut ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                style="display: {{ $isRunning ? 'none' : 'inline-block' }}" {{ $timeOut ? 'disabled' : '' }}>
                Mulai
            </button>

            {{-- Tombol Selesai --}}
            <button id="stopButton" wire:click="$set('showStopModal', true)"
                class="px-6 py-2 font-bold rounded 
                {{ !$isRunning || $timeOut ? 'bg-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 text-white' }}"
                style="display: {{ $isRunning ? 'inline-block' : 'none' }}"
                {{ !$isRunning || $timeOut ? 'disabled' : '' }}>
                Selesai
            </button>

            {{-- Tombol Dinas Keluar --}}
            <button id="dinasKeluarButton" wire:click="$set('showDinasModal', true)"
                class="px-6 py-2 font-bold rounded 
                bg-blue-600 hover:bg-blue-700 text-white">
                Dinas Keluar
            </button>
        </div>

        {{-- Modal untuk Mulai Timer --}}
        @if ($showStartModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Mulai Timer</h2>
                    <textarea wire:model.defer="deskripsi_in" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan deskripsi pekerjaan..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showStartModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        <button wire:click="startTimer"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Mulai</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal untuk Selesai Timer --}}
        @if ($showStopModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Selesai Timer</h2>
                    <textarea wire:model.live="deskripsi_out" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan hasil pekerjaan..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showStopModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        <button wire:click="stopTimer"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Selesai</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal untuk Dinas Keluar --}}
        @if ($showDinasModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Dinas Keluar</h2>
                    <textarea wire:model="deskripsi_dinas" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan keterangan dinas keluar..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showDinasModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        <button wire:click="dinasKeluar"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </div>
        @endif
        @push('scripts')
            <script type="module">
                window.addEventListener('alert-error', event => {

                    // Ambil langsung dari event.detail.message
                    const message = event.detail.message;

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>
        @endpush
    </div>
    @push('scripts')
        {{-- JavaScript Timer --}}
        <script type="module">
            let timer;
            let timeElapsed = 0;
            let isRunning = false;
            let isStopped = false;


            function updateTimerDisplay() {
                const days = Math.floor(timeElapsed / 86400); // 1 hari = 86400 detik
                const hours = Math.floor((timeElapsed % 86400) / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((timeElapsed % 3600) / 60).toString().padStart(2, '0');
                const seconds = (timeElapsed % 60).toString().padStart(2, '0');

                const display = document.getElementById('timerDisplay');

                if (days > 0) {
                    // Jika sudah lebih dari 24 jam → Tampilkan format dengan hari
                    display.innerText = `${days}d ${hours}:${minutes}:${seconds}`;
                } else {
                    // Jika masih dalam 24 jam → Format HH:mm:ss
                    display.innerText = `${hours}:${minutes}:${seconds}`;
                }

                // Efek tampilan jika timer berhenti
                if (isStopped) {
                    display.classList.add('text-gray-600', 'opacity-50');
                } else {
                    display.classList.remove('text-gray-600', 'opacity-50');
                }
            }

            // Fungsi untuk memulai timer
            function startTimer(startTimestamp) {
                if (!isRunning) {
                    isRunning = true;
                    isStopped = false;
                    // Jika startTimestamp kosong → mulai dari 0
                    timeElapsed = startTimestamp ?
                        Math.floor(Date.now() / 1000) - startTimestamp :
                        0;

                    updateTimerDisplay();

                    timer = setInterval(() => {
                        timeElapsed++;
                        updateTimerDisplay();
                    }, 1000);
                }
            }

            // Jika timer sudah berhenti → Hitung selisih langsung di frontend
            if (!@json($isRunning) && @json($timeOut)) {
                const startTime = new Date(@json($timeIn) * 1000); // Konversi ke milidetik
                const endTime = new Date(@json($timeOut) * 1000); // Konversi ke milidetik

                // Hitung selisih dalam detik
                timeElapsed = Math.floor((endTime - startTime) / 1000);
                console.log('Time Elapsed (Calculated):', timeElapsed);

                isRunning = false;
                isStopped = true;

                updateTimerDisplay();

                // Efek visual saat timer berhenti
                const display = document.getElementById('timerDisplay');
                display.classList.add('text-gray-600', 'opacity-50');
            }

            // Tangkap event dari Livewire untuk memulai timer
            window.addEventListener('timer-started', (event) => {
                startTimer(event.detail);
            });

            // Jika sudah ada `timeIn` dan status berjalan → Mulai otomatis
            if (@json($isRunning) && @json($timeIn)) {
                // console.log('Timer Resumed:', @json($timeIn));
                startTimer(@json($timeIn));
            }
        </script>
    @endpush

    {{-- Rencana Kerja --}}
    <h3 class="font-bold mt-4">RENCANA KERJA</h3>
    <p class="font-semibold text-gray-600">{{ $deskripsi_in ?? '-' }}</p>

    {{-- kerja Selesai --}}
    @if (!empty($deskripsi_out))
        <h3 class="font-bold mt-4">SELESAI KERJA</h3>
        <p class="font-semibold text-gray-600">{{ $deskripsi_out ?? '-' }}</p>
    @endif
</div>

<div>
    <h1 class="text-2xl font-bold text-success-900">Timer</h1>
    <div class="p-4 text-left">
        {{-- Tanggal --}}
        <p class="text-gray-500">{{ now()->format('l, d F Y') }}</p>

        <div id="mainTimerContainer" style="display: {{ $isLemburRunning ? 'none' : 'block' }};">
            {{-- Timer Utama --}}
            <h1 id="timerDisplay" class="text-8xl font-bold text-center">
                {{-- Display timer utama --}}
                00:00:00
            </h1>
        </div>

        <div id="lemburContainer" style="display: {{ $isLemburRunning ? 'block' : 'none' }};">
            {{-- Timer Lembur --}}
            <h1 id="lemburDisplay"
                class="text-6xl font-bold text-center text-yellow-500 bg-gray-100 py-4 rounded-md shadow-md">
                00:00:00
            </h1>
        </div>

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
                Dinas Luar
            </button>
            {{-- Tombol Mulai Lembur --}}
            <button wire:click="openLemburModal"
                class="px-6 py-2 font-bold rounded bg-yellow-500 hover:bg-yellow-700 text-white"
                style="display: {{ !$isLemburRunning && $timeOut ? 'inline-block' : 'none' }}">
                Mulai Lembur
            </button>

            {{-- Tombol Selesai Lembur --}}
            <button wire:click="stopLemburMandiri"
                class="px-6 py-2 font-bold rounded bg-red-500 hover:bg-red-700 text-white"
                style="display: {{ $isLemburRunning ? 'inline-block' : 'none' }}">
                Selesai Lembur
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
                    <textarea wire:model.defer="deskripsi_out" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan hasil pekerjaan..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showStopModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        <button wire:click="openWorkReportModal"
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

        {{-- Modal untuk Lembur --}}
        @if ($showOvertimeModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Lembur</h2>
                    <textarea wire:model.live="deskripsi_lembur" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan keterangan lembur..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showOvertimeModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">
                            Batal
                        </button>
                        <button wire:click="saveOvertime"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan Lembur
                        </button>
                    </div>
                </div>
            </div>
        @endif
        @if ($showLemburModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Mulai Lembur</h2>
                    <textarea wire:model.defer="deskripsi_lembur" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan deskripsi lembur..."></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showLemburModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">
                            Batal
                        </button>
                        <button wire:click="startLemburMandiri"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-700">
                            Simpan
                        </button>
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

                window.addEventListener('alert-success', event => {

                    // Ambil langsung dari event.detail.message
                    const message = event.detail.message;

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil...',
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

            let timerLembur;
            let timeElapsedLembur = 0;
            let isLemburRunning = false;

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

            // Jika timer utama sudah berhenti → Hitung selisih langsung di frontend
            if (!@json($isRunning) && @json($timeOut)) {
                const startTime = new Date(@json($timeIn) * 1000); // Konversi ke milidetik
                const endTime = new Date(@json($timeOut) * 1000); // Konversi ke milidetik

                // ✅ Hitung selisih kerja utama dalam detik
                timeElapsed = Math.floor((endTime - startTime) / 1000);

                // ✅ Jika ada lembur → Tambahkan ke hasil akhir
                if (@json($timeInLembur) && @json($timeOutLembur)) {
                    const lemburStart = new Date(@json($timeInLembur) * 1000);
                    const lemburEnd = new Date(@json($timeOutLembur) * 1000);

                    // Hitung selisih waktu lembur
                    const lemburElapsed = Math.floor((lemburEnd - lemburStart) / 1000);

                    // ✅ Gabungkan hasil lembur ke hasil utama
                    timeElapsed += lemburElapsed;
                }

                isRunning = false;
                isStopped = true;

                updateTimerDisplay();

                // Efek visual saat timer berhenti
                const display = document.getElementById('timerDisplay');
                display.classList.add('text-gray-600', 'opacity-50');
            }

            function updateLemburDisplay() {
                const hours = Math.floor(timeElapsedLembur / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((timeElapsedLembur % 3600) / 60).toString().padStart(2, '0');
                const seconds = (timeElapsedLembur % 60).toString().padStart(2, '0');

                const display = document.getElementById('lemburDisplay');
                if (display) {
                    display.innerText = `${hours}:${minutes}:${seconds}`;
                }
            }


            function startLemburTimer(startTimestamp) {
                if (!isLemburRunning) {
                    isLemburRunning = true;

                    // ✅ Hitung selisih waktu lembur dari `startTimestamp`
                    timeElapsedLembur = startTimestamp ?
                        Math.floor(Date.now() / 1000) - startTimestamp :
                        0;

                    updateLemburDisplay();

                    // ✅ Jalankan timer lembur setiap detik
                    timerLembur = setInterval(() => {
                        timeElapsedLembur++;
                        updateLemburDisplay();
                    }, 1000);
                }
            }

            // Update timer lembur berdasarkan data `timeInLembur` dari backend
            if (@json($isLemburRunning) && @json($timeInLembur)) {
                const lemburStart = new Date(@json($timeInLembur) * 1000); // Convert from seconds to milliseconds
                const currentTime = new Date();
                const lemburDuration = Math.floor((currentTime - lemburStart) / 1000); // Calculate elapsed time

                timeElapsedLembur = lemburDuration;
                updateLemburDisplay();
            }


            function stopLemburTimer() {
                isLemburRunning = false;
            }

            // Tangkap event dari Livewire untuk memulai timer
            window.addEventListener('timer-started', (event) => {
                startTimer(event.detail);
            });

            // Event untuk memulai timer lembur dari Livewire
            window.addEventListener('timer-lembur-started', (event) => {
                startLemburTimer(event.detail);
            });

            window.addEventListener('timer-lembur-stopped', () => {
                stopLemburTimer();
            });

            // Jika sudah ada `timeIn` dan status berjalan → Mulai otomatis
            if (@json($isRunning) && @json($timeIn)) {
                // console.log('Timer Resumed:', @json($timeIn));
                startTimer(@json($timeIn));
            }

            // ✅ Jika timer lembur sudah berjalan → Mulai otomatis
            if (@json($isLemburRunning) && @json($timeInLembur)) {
                console.log(@json($timeInLembur));
                startLemburTimer(@json($timeInLembur));
            }
        </script>
    @endpush
    {{-- Rencana Kerja --}}
    <h3 class="font-bold mt-4">RENCANA KERJA</h3>
    <p class="font-semibold text-gray-600">{{ $this->absensiTanpaLembur->first()->deskripsi_in ?? '-' }}</p>

    {{-- kerja Selesai --}}
    @if (!empty($this->absensiTanpaLembur->first()->deskripsi_out))
        <h3 class="font-bold mt-4">SELESAI KERJA</h3>
        <p class="font-semibold text-gray-600">{{ $this->absensiTanpaLembur->first()->deskripsi_out ?? '-' }}</p>
    @endif

    {{-- <div>
        <!-- Menampilkan deskripsi_in dan deskripsi_out jika is_lembur = false -->
        @if ($this->absensiTanpaLembur->isNotEmpty())
            <ul>
                @foreach ($this->absensiTanpaLembur as $item)
                    <li>
                        <strong>Deskripsi In:</strong> {{ $item->deskripsi_in }} <br>
                        <strong>Deskripsi Out:</strong> {{ $item->deskripsi_out }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>Tidak ada absensi yang tidak lembur.</p>
        @endif
    </div> --}}
</div>

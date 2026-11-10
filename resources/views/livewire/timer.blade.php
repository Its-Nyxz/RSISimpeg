<div>
    <div class="p-4 text-left">
        {{-- Tanggal --}}
        <p class="text-gray-500">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>

        <div id="mainTimerContainer" style="display: {{ $isLemburRunning ? 'none' : 'block' }};">
            {{-- Timer Utama --}}
            <h1 id="timerDisplay"
                class="font-bold text-center 
                    text-4xl sm:text-6xl lg:text-8xl   {{-- Ukuran lebih kecil di mobile --}}
                    py-2 sm:py-4">
                {{-- Display timer utama --}}
                00:00:00
            </h1>
            @if($hasLemburHistoryBeforeShift)
                <div class="text-center text-gray-500 text-md font-semibold -mt-2 sm:-mt-4 mb-2">
                    <span>
                        riwayat lembur sebelum shift ditemukan
                    </span><br>
                    <span>
                        Durasi Lembur: {{ $durasiLembur ? gmdate('H:i:s', $durasiLembur) : '00:00:00' }}
                    </span>
                </div>
            @endif
        </div>

        <div id="lemburContainer" style="display: {{ $isLemburRunning ? 'block' : 'none' }};">
            {{-- Timer Lembur --}}
            <h1 id="lemburDisplay"
                class="font-bold text-center 
                    text-3xl sm:text-5xl lg:text-6xl {{-- Ukuran lembur lebih kecil di mobile --}}
                    text-yellow-500 
                    bg-gray-100 
                    py-2 sm:py-4 
                    rounded-md 
                    shadow-md">
                00:00:00
            </h1>
        </div>

        <div class="px-4 py-6 text-center">
            <div class="flex flex-col items-center space-y-2">
                {{-- Tombol Mulai --}}
                <button id="startButton" wire:click="$set('showStartModal', true)"
                    class="px-6 py-2 font-bold rounded 
                {{ $timeOut ? 'bg-gray-400 cursor-not-allowed' : 'bg-success-600 hover:bg-success-700 text-white' }}"
                    style="display: {{ $isRunning || $isLemburRunning || $timeOut ? 'none' : 'inline-block' }}" {{ $timeOut ? 'disabled' : '' }}>
                    Mulai
                </button>

                {{-- Tombol Selesai --}}
                <button id="stopButton" wire:click="$set('showStopModal', true)"
                    class="px-6 py-2 font-bold rounded 
                {{ !$isRunning || $timeOut ? 'bg-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 text-white' }}"
                    style="display: {{ $isRunning && !$isLemburRunning || $timeOut ? 'inline-block' : 'none' }}"
                    {{ !$isRunning || $timeOut ? 'disabled' : '' }}>
                    Selesai
                </button>
                {{-- Tombol Dinas Luar --}}
                <button id="dinasKeluarButton" wire:click="$set('showDinasModal', true)"
                    class="px-6 py-2 font-bold rounded bg-blue-600 hover:bg-blue-700 text-white"
                    style="display : {{ !$isLemburRunning && !$isRunning ? 'inline-block' : 'none' }}">
                    Dinas Luar
                </button>

                {{-- Tombol Mulai Lembur --}}
                <button wire:click="openLemburModal"
                    class="px-6 py-2 font-bold rounded bg-yellow-500 hover:bg-yellow-700 text-white"
                    style="display: {{ !$isLemburRunning && !$isRunning ? 'inline-block' : 'none' }}">
                    Mulai Lembur
                </button>

                {{-- Tombol Selesai Lembur --}}
                <button wire:click="stopLemburMandiri"
                    class="px-6 py-2 font-bold rounded bg-red-500 hover:bg-red-700 text-white"
                    style="display: {{ $isLemburRunning ? 'inline-block' : 'none' }}">
                    Selesai Lembur
                </button>
            </div>
        </div>

        {{-- Modal untuk Mulai Timer --}}
        @if ($showStartModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-md w-96">
                    <h2 class="text-xl font-bold mb-4">Mulai Timer</h2>
                    <textarea wire:model.defer="deskripsi_in" class="w-full p-2 border rounded-md"
                        placeholder="Masukkan deskripsi pekerjaan..." required></textarea>
                    @error('deskripsi_in')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showStartModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        {{-- <button wire:click="startTimer"
                            class="px-4 py-2 bg-success-600 text-white rounded-md hover:bg-success-700">Mulai</button> --}}
                        <button onclick="kirimLokasiKeLivewire('start')"
                            class="px-4 py-2 bg-success-600 text-white rounded-md hover:bg-success-700">
                            Mulai
                        </button>
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
                        placeholder="Masukkan hasil pekerjaan..." required></textarea>
                    @error('deskripsi_out')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showStopModal', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                        {{-- <button wire:click="openWorkReportModal"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Selesai</button> --}}
                        <button onclick="kirimLokasiKeLivewire('stop')"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Selesai
                        </button>
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

                    <div class="mt-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model.live="akanKembali" class="form-checkbox text-blue-600">
                            <span class="text-sm text-gray-700">Saya akan kembali bekerja setelah dinas</span>
                        </label>
                    </div>

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
                    // const message = event.detail.message;
                    const message = event.detail[0]?.message || event.detail?.message || event.detail || 'Terjadi kesalahan';

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#d33'
                    });
                });

                window.addEventListener('alert-success', event => {

                    // Ambil langsung dari event.detail.message
                    // const message = event.detail.message;
                    const message = event.detail[0]?.message || event.detail?.message || event.detail || 'Berhasil';

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

            // Offset untuk mengatasi jam komputer user yang tidak akurat
            const serverTime = @json(now()->timestamp);
            const clientTime = Math.floor(Date.now() / 1000);
            const timeOffset = serverTime - clientTime;

            function getRealTime() {
                return Math.floor(Date.now() / 1000) + timeOffset;
            }

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

            let activeStartTimestamp = null;
            let activeLemburTimestamp = null;

            // Fungsi untuk memulai timer
            function startTimer(startTimestamp) {
                activeStartTimestamp = startTimestamp ? parseInt(startTimestamp) : null;
                if (!isRunning) {
                    isRunning = true;
                    isStopped = false;
                    timeElapsed = activeStartTimestamp ?
                        Math.max(0, getRealTime() - activeStartTimestamp) :
                        0;

                    updateTimerDisplay();

                    if (timer) clearInterval(timer);
                    timer = setInterval(() => {
                        if (activeStartTimestamp) {
                            timeElapsed = Math.max(0, getRealTime() - activeStartTimestamp);
                        } else {
                            timeElapsed++;
                        }
                        updateTimerDisplay();
                    }, 1000);
                }
            }

            // Jika timer utama sudah berhenti → Hitung selisih langsung di frontend
            if (!@json($isRunning) && @json($timeOut)) {
                // const startTime = new Date(@json($timeIn) * 1000); // Konversi ke milidetik
                // const endTime = new Date(@json($timeOut) * 1000); // Konversi ke milidetik

                // ✅ Hitung selisih kerja utama dalam detik
                // timeElapsed = Math.floor((endTime - startTime) / 1000);
                timeElapsed = @json($timeOut) - @json($timeIn);

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
                activeLemburTimestamp = startTimestamp ? parseInt(startTimestamp) : null;
                if (!isLemburRunning) {
                    isLemburRunning = true;

                    // ✅ Hitung selisih waktu lembur dari `startTimestamp`
                    timeElapsedLembur = activeLemburTimestamp ?
                        Math.max(0, getRealTime() - activeLemburTimestamp) :
                        0;

                    updateLemburDisplay();

                    if (timerLembur) clearInterval(timerLembur);
                    // ✅ Jalankan timer lembur setiap detik dengan real time
                    timerLembur = setInterval(() => {
                        if (activeLemburTimestamp) {
                            timeElapsedLembur = Math.max(0, getRealTime() - activeLemburTimestamp);
                        } else {
                            timeElapsedLembur++;
                        }
                        updateLemburDisplay();
                    }, 1000);
                }
            }

            // Update timer lembur berdasarkan data `timeInLembur` dari backend
            if (@json($isLemburRunning) && @json($timeInLembur)) {
                startLemburTimer(@json($timeInLembur));
            }


            function stopLemburTimer() {
                isLemburRunning = false;
                if (timerLembur) clearInterval(timerLembur);
            }

            // Tangkap event dari Livewire untuk memulai timer & perbarui UI tombol seketika
            window.addEventListener('timer-started', (event) => {
                const startTimestamp = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                // Jalankan timer
                startTimer(startTimestamp);

                // Secara reaktif langsung sembunyikan tombol Mulai dan munculkan Selesai
                const startBtn = document.getElementById('startButton');
                const stopBtn = document.getElementById('stopButton');
                const dinasBtn = document.getElementById('dinasKeluarButton');
                if (startBtn) startBtn.style.display = 'none';
                if (dinasBtn) dinasBtn.style.display = 'none';
                if (stopBtn) {
                    stopBtn.style.display = 'inline-block';
                    stopBtn.disabled = false;
                    stopBtn.classList.remove('bg-gray-600', 'cursor-not-allowed');
                    stopBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'text-white');
                }
            });

            // Tangkap event dari Livewire untuk menghentikan timer
            window.addEventListener('timer-stopped', () => {
                isRunning = false;
                isStopped = true;
                if (timer) clearInterval(timer);
                updateTimerDisplay();

                const startBtn = document.getElementById('startButton');
                const stopBtn = document.getElementById('stopButton');
                if (startBtn) {
                    startBtn.style.display = 'inline-block';
                    startBtn.disabled = true;
                    startBtn.classList.remove('bg-success-600', 'hover:bg-success-700', 'text-white');
                    startBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                }
                if (stopBtn) stopBtn.style.display = 'none';
            });

            // Event untuk memulai timer lembur dari Livewire
            window.addEventListener('timer-lembur-started', (event) => {
                startLemburTimer(event.detail);
            });

            window.addEventListener('timer-lembur-stopped', () => {
                stopLemburTimer();
            });

            // Sinkronisasi otomatis saat HP bangun dari mode tidur/lock screen
            const sinkronkanWaktuSetelahBangun = () => {
                if (isRunning && activeStartTimestamp) {
                    timeElapsed = Math.max(0, getRealTime() - activeStartTimestamp);
                    updateTimerDisplay();
                }
                if (isLemburRunning && activeLemburTimestamp) {
                    timeElapsedLembur = Math.max(0, getRealTime() - activeLemburTimestamp);
                    updateLemburDisplay();
                }
            };

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) sinkronkanWaktuSetelahBangun();
            });
            window.addEventListener('pageshow', sinkronkanWaktuSetelahBangun);
            window.addEventListener('focus', sinkronkanWaktuSetelahBangun);

            // Jika sudah ada `timeIn` dan status berjalan → Mulai otomatis
            if (@json($isRunning) && @json($timeIn)) {
                startTimer(@json($timeIn));
            }

            // ✅ Jika timer lembur sudah berjalan → Mulai otomatis
            if (@json($isLemburRunning) && @json($timeInLembur)) {
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


    @push('scripts')
        <script>
            let lokasiTerakhir = null;

            function ambilKoordinatAkurat() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        return reject('Browser Anda tidak mendukung Geolocation/GPS.');
                    }

                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            const dataLokasi = {
                                lat: pos.coords.latitude,
                                lng: pos.coords.longitude,
                                alt: pos.coords.altitude,
                                acc: pos.coords.accuracy
                            };
                            lokasiTerakhir = dataLokasi;
                            console.log("📍 Lokasi Terkunci:", dataLokasi.lat, dataLokasi.lng, "Akurasi:", dataLokasi.acc);
                            resolve(dataLokasi);
                        },
                        function(err) {
                            let pesan = 'Izin lokasi dibutuhkan untuk absensi.';
                            if (err.code === 1) {
                                pesan = 'Izin akses lokasi (GPS) ditolak oleh browser. Mohon aktifkan izin lokasi di setelan browser.';
                            } else if (err.code === 2) {
                                pesan = 'Sinyal lokasi/GPS tidak terdeteksi. Silakan coba di tempat yang lebih terbuka.';
                            } else if (err.code === 3) {
                                pesan = 'Waktu pencarian sinyal GPS habis (Timeout). Mohon coba beberapa saat lagi.';
                            }
                            reject(pesan);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                });
            }

            function apakahPerangkatMobile() {
                // 1. Cek User Agent standar
                let uaMobile = /android|iphone|ipad|ipod|mobile|blackberry|iemobile|opera mini/i.test(navigator.userAgent);
                if (uaMobile) return true;

                // 2. Deteksi iPad / iPhone yang menyamar sebagai Mac di Safari (Desktop Mode)
                // Spesifikasi resmi WebKit Apple: platform MacIntel + maxTouchPoints > 1
                let isAppleTouch = (navigator.platform === 'MacIntel' || (navigator.userAgent && navigator.userAgent.includes('Macintosh'))) && (navigator.maxTouchPoints > 1);
                if (isAppleTouch) return true;

                // 3. Deteksi fisik layar sentuh smartphone (touch device dengan lebar layar <= 768px)
                let isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
                let isSmallScreen = (window.innerWidth <= 768) || (window.screen.width <= 768);
                if (isTouch && isSmallScreen) return true;

                return false;
            }

            // Fungsi kirim lokasi ke Livewire
            window.kirimLokasiKeLivewire = async function(aksi = 'start') {
                let isMobile = apakahPerangkatMobile();
                let isWhitelisted = @json($isIpWhitelisted);

                const showSavingLoading = (aksiName) => {
                    Swal.fire({
                        title: aksiName === 'start' ? 'Menyimpan Presensi Masuk...' : 'Menyimpan Presensi Pulang...',
                        html: '<div class="py-2 text-center">' +
                              '<p class="text-sm text-gray-600 mb-3">Mohon tunggu sebentar, data sedang diverifikasi & dicatat ke server.</p>' +
                              '<div class="p-3 bg-amber-50 border border-amber-300 rounded-lg text-amber-900 text-xs font-semibold leading-relaxed shadow-sm">' +
                              '⚠️ <strong>PENTING:</strong> Mohon JANGAN mengunci layar HP atau menutup browser sebelum proses selesai.' +
                              '</div></div>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                };

                // Jika terhubung ke IP/WiFi Kantor: Loloskan langsung
                if (isWhitelisted) {
                    showSavingLoading(aksi);
                    let payload = { is_mobile: isMobile };
                    if (aksi === 'start') {
                        @this.call('startTimer', false, null, payload);
                    } else {
                        @this.call('openWorkReportModal', payload);
                    }
                    return;
                }

                // Jika bukan di WiFi kantor DAN bukan perangkat mobile (misal: Laptop/PC di rumah): Blokir
                if (!isMobile) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gunakan Smartphone (HP) untuk absensi.',
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                // Mobile di luar WiFi kantor: Wajib kunci GPS akurat
                Swal.fire({
                    title: 'Mengunci Titik GPS...',
                    text: 'Mohon tunggu sebentar, satelit sedang membaca koordinat presisi Anda.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const lokasi = await ambilKoordinatAkurat();
                    lokasi.is_mobile = true;
                    showSavingLoading(aksi);

                    if (aksi === 'start') {
                        @this.call('startTimer', false, null, lokasi);
                    } else {
                        @this.call('openWorkReportModal', lokasi);
                    }
                } catch (pesanError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengunci Lokasi',
                        text: pesanError,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#d33'
                    });
                }
            };

            document.addEventListener('DOMContentLoaded', () => {
                let isMobile = apakahPerangkatMobile();
                let isWhitelisted = @json($isIpWhitelisted);
                // Pre-warm GPS saat halaman pertama kali dibuka di perangkat mobile
                if (isMobile && !isWhitelisted) {
                    ambilKoordinatAkurat().catch(() => {});
                }
            });
        </script>
    @endpush

</div>

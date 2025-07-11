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

                {{-- Tombol Dinas Luar --}}
                <button id="dinasKeluarButton" wire:click="$set('showDinasModal', true)"
                    class="px-6 py-2 font-bold rounded bg-blue-600 hover:bg-blue-700 text-white">
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
                        {{-- <button wire:click="startTimer"
                            class="px-4 py-2 bg-success-600 text-white rounded-md hover:bg-success-700">Mulai</button> --}}
                        <button onclick="kirimLokasiKeLivewire()"
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
                        placeholder="Masukkan hasil pekerjaan..."></textarea>
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
                const startTimestamp = event.detail;

                if (!isRunning) {
                    timeElapsed = Math.floor(Date.now() / 1000) - startTimestamp;
                    timer = setInterval(() => {
                        timeElapsed++;
                        updateTimerDisplay();
                    }, 1000);
                }
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


    @push('scripts')
        <script>
            // const lokasiKantor = {
            //     lat: -7.402330130327286,
            //     lng: 109.615622721266,
            //     radiusMeter: 100
            // };

            const areaPolygons = {
                "RSI": [{
                        lat: -7.401462324660784,
                        lng: 109.61574443318705
                    },
                    {
                        lat: -7.40146214270284,
                        lng: 109.6157440761346
                    },
                    {
                        lat: -7.4017712054383935,
                        lng: 109.61499327224521
                    },
                    {
                        lat: -7.403230824029308,
                        lng: 109.61515910978147
                    },
                    {
                        lat: -7.403165037042953,
                        lng: 109.61580592184652
                    },
                    {
                        lat: -7.402782968146411,
                        lng: 109.6164214758092
                    },
                    {
                        lat: -7.401966177920016,
                        lng: 109.61618451323585
                    },
                    {
                        lat: -7.40206468637885,
                        lng: 109.61591235565817
                    },
                    {
                        lat: -7.401462324660784,
                        lng: 109.61574443318705
                    }
                ],
                "Akunbiz": [{
                        lat: -7.548413507144458,
                        lng: 110.81252863588689
                    },
                    {
                        lat: -7.548802885218547,
                        lng: 110.81283553967711
                    },
                    {
                        lat: -7.548381621290859,
                        lng: 110.8132859282019
                    },
                    {
                        lat: -7.548040287781695,
                        lng: 110.8129615051734
                    },
                    {
                        lat: -7.548413507144458,
                        lng: 110.81252863588689
                    }
                ],
                "Rumah": [{
                        lat: -7.603560911411364,
                        lng: 110.78382576729706
                    },
                    {
                        lat: -7.603661241186458,
                        lng: 110.78382576729706
                    },
                    {
                        lat: -7.603661241186458,
                        lng: 110.78398304726369
                    },
                    {
                        lat: -7.603560911411364,
                        lng: 110.78398304726369
                    },
                    {
                        lat: -7.603560911411364,
                        lng: 110.78382576729706
                    }
                ],
                "Poliklinik": [{
                        lat: -7.401821225185401,
                        lng: 109.61501131827964
                    },
                    {
                        lat: -7.402030471704805,
                        lng: 109.61503914309628
                    },
                    {
                        lat: -7.401977585231165,
                        lng: 109.61537304089137
                    },
                    {
                        lat: -7.401747643968704,
                        lng: 109.61530347885127
                    },
                    {
                        lat: -7.401821225185401,
                        lng: 109.61501131827964
                    }
                ],
                "Al Zaitun": [{
                        lat: -7.402653611845423,
                        lng: 109.615097111463
                    },
                    {
                        lat: -7.4028467621173775,
                        lng: 109.61511334260632
                    },
                    {
                        lat: -7.4028145704119765,
                        lng: 109.61525246668793
                    },
                    {
                        lat: -7.402623719534873,
                        lng: 109.61521304819797
                    },
                    {
                        lat: -7.402653611845423,
                        lng: 109.615097111463
                    }
                ],
                "Assalam": [{
                        lat: -7.402324796309088,
                        lng: 109.61547042774959
                    },
                    {
                        lat: -7.402485754994245,
                        lng: 109.61550289003463
                    },
                    {
                        lat: -7.402446665033025,
                        lng: 109.61564433285128
                    },
                    {
                        lat: -7.402306401026351,
                        lng: 109.61560723309623
                    },
                    {
                        lat: -7.402324796309088,
                        lng: 109.61547042774959
                    }
                ],
                "Al Amin": [{
                        lat: -7.402980127731013,
                        lng: 109.6153057975863
                    },
                    {
                        lat: -7.403101996273804,
                        lng: 109.61532898493294
                    },
                    {
                        lat: -7.403028415270782,
                        lng: 109.61549129636131
                    },
                    {
                        lat: -7.402936439000513,
                        lng: 109.61543564672803
                    },
                    {
                        lat: -7.402980127731013,
                        lng: 109.6153057975863
                    }
                ],
                "As Syfa": [{
                        lat: -7.402885852043113,
                        lng: 109.61561187056475
                    },
                    {
                        lat: -7.403049109930095,
                        lng: 109.61560259562634
                    },
                    {
                        lat: -7.403039912303285,
                        lng: 109.61578577566792
                    },
                    {
                        lat: -7.402883552635544,
                        lng: 109.61576258831974
                    },
                    {
                        lat: -7.402885852043113,
                        lng: 109.61561187056475
                    }
                ]
            };

            let lokasiTerakhir = null;
            let sudahPeringatkan = {
                gerak: false,
                emulator: false,
                devtools: false
            };

            function hitungJarakMeter(lat1, lon1, lat2, lon2) {
                const R = 6371000;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) ** 2 +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) ** 2;
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function isInsidePolygon(point, polygon) {
                let x = point.lat,
                    y = point.lng;
                let inside = false;

                for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
                    let xi = polygon[i].lat,
                        yi = polygon[i].lng;
                    let xj = polygon[j].lat,
                        yj = polygon[j].lng;

                    let intersect = ((yi > y) !== (yj > y)) &&
                        (x < (xj - xi) * (y - yi) / (yj - yi + 1e-10) + xi);
                    if (intersect) inside = !inside;
                }

                return inside;
            }

            function simpanLokasi(lat, lng) {
                const prev = JSON.parse(localStorage.getItem('lokasi_sebelumnya'));
                const now = {
                    lat,
                    lng,
                    waktu: Date.now()
                };

                if (prev) {
                    const jarak = hitungJarakMeter(lat, lng, prev.lat, prev.lng);
                    const waktu = (now.waktu - prev.waktu) / 1000;

                    if (jarak > 1000 && waktu < 30 && !sudahPeringatkan.gerak) {
                        sudahPeringatkan.gerak = true;
                        Swal.fire({
                            icon: 'warning',
                            title: '🚨 Gerakan Mencurigakan',
                            text: `Berpindah ${Math.round(jarak)} meter dalam ${waktu} detik.`
                        });
                    }
                }

                localStorage.setItem('lokasi_sebelumnya', JSON.stringify(now));
            }

            function deteksiEmulator() {
                const agents = ['Genymotion', 'Emulator', 'SDK', 'Android SDK built', 'X86'];
                if (!sudahPeringatkan.emulator && agents.some(agent => navigator.userAgent.includes(agent))) {
                    sudahPeringatkan.emulator = true;
                    Swal.fire({
                        icon: 'warning',
                        title: '⚠️ Emulator Terdeteksi',
                        text: 'Anda tampaknya menggunakan emulator.'
                    });
                }
            }

            function deteksiDevTools() {
                const element = new Image();
                Object.defineProperty(element, 'id', {
                    get: function() {
                        if (!sudahPeringatkan.devtools) {
                            sudahPeringatkan.devtools = true;
                            Swal.fire({
                                icon: 'warning',
                                title: '⚠️ DevTools Aktif',
                                text: 'Developer Tools terdeteksi. Data mungkin tidak valid.'
                            });
                        }
                    }
                });
                // console.log(element);
            }

            function ambilLokasiTerbaru() {
                if (!navigator.geolocation) {
                    Swal.fire('Error', 'Browser tidak mendukung Geolocation.', 'error');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        const {
                            latitude,
                            longitude,
                            accuracy
                        } = pos.coords;
                        lokasiTerakhir = {
                            lat: latitude,
                            lng: longitude,
                            accuracy
                        };

                        simpanLokasi(latitude, longitude);
                        deteksiEmulator();
                        deteksiDevTools();
                        console.log("Latitude:", latitude);
                        console.log("Longitude:", longitude);
                    },
                    function() {
                        Swal.fire('Gagal', 'Izin lokasi dibutuhkan.', 'error');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 30000
                    }
                );
            }

            // function validasiJarak(lat, lng) {
            //     const jarak = hitungJarakMeter(lat, lng, lokasiKantor.lat, lokasiKantor.lng);
            //     return {
            //         jarak,
            //         valid: jarak <= lokasiKantor.radiusMeter
            //     };
            // }

            function validasiLokasiPolygon(lat, lng) {
                const point = {
                    lat,
                    lng
                };

                for (const [namaArea, polygon] of Object.entries(areaPolygons)) {
                    if (isInsidePolygon(point, polygon)) {
                        return {
                            valid: true,
                            lokasi: namaArea
                        };
                    }
                }

                return {
                    valid: false,
                    lokasi: 'Luar Area'
                };
            }

            window.kirimLokasiKeLivewire = function(aksi = 'start') {
                if (!lokasiTerakhir) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menunggu Lokasi',
                        text: 'Sistem sedang mencoba mendeteksi lokasi Anda. Mohon tunggu beberapa saat.',
                    });
                    return;
                }

                // const hasilValidasi = validasiJarak(lokasiTerakhir.lat, lokasiTerakhir.lng);

                const hasilValidasi = validasiLokasiPolygon(lokasiTerakhir.lat, lokasiTerakhir.lng);
                console.log("📍 Lokasi valid di area:", hasilValidasi.lokasi);

                // Jika jarak tidak valid DAN lokasi terakhir belum diperbarui dalam 15 detik
                const lastUpdate = JSON.parse(localStorage.getItem('lokasi_sebelumnya'));
                const now = Date.now();
                const ageInSeconds = lastUpdate ? (now - lastUpdate.waktu) / 1000 : null;
                console.log("Usia lokasi terakhir:", ageInSeconds, "detik");

                // if (!hasilValidasi.valid) {
                //     if (ageInSeconds !== null && ageInSeconds < 15) {
                //         Swal.fire({
                //             icon: 'info',
                //             title: 'Menunggu Lokasi Akurat',
                //             text: 'Lokasi belum terdeteksi dengan akurat. Silakan tunggu beberapa saat dan coba kembali.',
                //             timer: 3000,
                //             showConfirmButton: false
                //         });
                //     } else {
                //         Swal.fire({
                //             icon: 'warning',
                //             title: 'Di Luar Area RSI Banjarnegara',
                //             text: `Jarak Anda: ${Math.round(hasilValidasi.jarak)} meter dari area kantor.`,
                //             timer: 3000,
                //             showConfirmButton: false
                //         });
                //     }
                //     return;
                // }

                @this.set('latitude', lokasiTerakhir.lat);
                @this.set('longitude', lokasiTerakhir.lng);

                if (aksi === 'start') {
                    @this.call('startTimer');
                } else if (aksi === 'stop') {
                    @this.call('openWorkReportModal');
                }
            };

            document.addEventListener('DOMContentLoaded', () => {
                // Ambil lokasi pertama kali
                ambilLokasiTerbaru();

                // Pastikan `lokasiTerakhir` tersedia sebelum memungkinkan aksi absensi
                let cekInterval = setInterval(() => {
                    if (lokasiTerakhir) {
                        clearInterval(cekInterval);
                        console.log('✅ Lokasi awal berhasil didapat.');
                    } else {
                        console.log('⏳ Menunggu lokasi awal...');
                    }
                }, 2000); // cek setiap 2 detik

                // Refresh lokasi setiap 60 detik
                setInterval(ambilLokasiTerbaru, 30000);
            });
        </script>
    @endpush
</div>

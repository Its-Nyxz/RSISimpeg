<x-body>
    @if ($jadwals->isEmpty())
        <div class="text-gray-500 text-center mt-10">Tidak ada jadwal absensi hari ini.</div>
    @else
        {{-- Carousel Container --}}
        <div class="relative w-full max-w-5xl mx-auto px-4 py-6">
            {{-- Carousel Viewport (penting: overflow-x-hidden) --}}
            <div class="overflow-x-hidden">
                {{-- Carousel Inner (slide wrapper) --}}
                <div id="carousel-inner" class="flex transition-transform duration-500 ease-in-out" style="width: 100%;">

                    @foreach ($jadwals as $jadwal)
                        {{-- Slide --}}
                        <div class="min-w-full flex-shrink-0 px-4 box-border">
                            <div class="p-6 rounded-lg border border-gray-200 shadow-md bg-white h-full">
                                <div class="mb-4 text-lg font-semibold text-gray-800">
                                    Jadwal Shift: {{ $jadwal->shift->nama_shift }}
                                    <span class="block text-sm text-gray-500">
                                        ({{ $jadwal->shift->jam_masuk }} - {{ $jadwal->shift->jam_keluar }})
                                    </span>
                                </div>

                                {{-- Timer Livewire --}}
                                <livewire:timer :jadwal_id="$jadwal->id" :wire:key="'timer-' . $jadwal->id" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Navigasi --}}
            <div class="absolute inset-y-0 left-0 flex items-center z-10 px-2">
                <button onclick="showPrev()"
                    class="bg-gray-600 hover:bg-gray-800 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-md focus:outline-none">
                    ‹
                </button>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center z-10 px-2">
                <button onclick="showNext()"
                    class="bg-green-600 hover:bg-green-800 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-md focus:outline-none">
                    ›
                </button>
            </div>

            {{-- Indikator --}}
            <div class="mt-6 text-center text-sm text-green-600">
                Menampilkan shift ke-<span id="shift-number">1</span> dari {{ count($jadwals) }}
            </div>
        </div>

        {{-- Script Carousel --}}
        <script>
            const totalSlides = {{ count($jadwals) }};
            const carouselInner = document.getElementById('carousel-inner');
            const shiftNum = document.getElementById('shift-number');
            const jadwalIds = @json($jadwals->pluck('id')); // [1901, 1902, 1903, ...]
            let currentIndex = 0;

            function updateCarousel() {
                const offset = -currentIndex * 100;
                carouselInner.style.transform = `translateX(${offset}%)`;
                shiftNum.textContent = currentIndex + 1;

                updateActiveComponent();
            }

            function updateActiveComponent() {
                const activeId = 'timer-' + jadwalIds[currentIndex];

                console.log(`🌀 Geser ke shift index: ${currentIndex} → Komponen Aktif: ${activeId}`);

                Livewire.dispatch('activate-timer', {
                    activeId: 'timer-' + jadwalIds[currentIndex]
                });
            }

            window.showNext = function() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            };

            window.showPrev = function() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
            };

            document.addEventListener('livewire:load', () => {
                Livewire.hook('message.processed', () => {
                    updateCarousel();
                });
            });
        </script>

    @endif
</x-body>

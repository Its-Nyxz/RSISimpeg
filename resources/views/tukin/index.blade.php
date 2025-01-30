<x-body>
    <style>
        /* Tambahkan x-cloak agar elemen tetap ada di DOM */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div>
        <div class="text-5xl font-regular mb-6 text-success-600">Tunjangan Kinerja</div>

        <!-- Alpine.js for Tab Management -->
        <div x-data="{ activeTab: 'masakerja' }" class="w-full bg-white border border-gray-200 rounded-lg shadow">
            <!-- Tabs -->
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-success-300"
                role="tablist">
                <li class="me-2">
                    <button @click="activeTab = 'masakerja'; $wire.updateSearch()"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'masakerja' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Masa Kerja
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'levelunit'; $wire.updateSearch()"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'levelunit' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Level Unit
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'proposionalitas'; $wire.updateSearch()"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'proposionalitas' ? 'bg-green-500 text-white' :
                            'hover:text-white hover:bg-success-300'">
                        Proposionalitas
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'pointperan'; $wire.updateSearch()"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'pointperan' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Point Peran Fungsionalitas
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'tukinjabatan'; $wire.updateSearch()"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'tukinjabatan' ? 'bg-green-500 text-white' :
                            'hover:text-white hover:bg-success-300'">
                        Jabatan
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div id="defaultTabContent">
                <!-- Masa Kerja -->
                <div x-show="activeTab === 'masakerja'" x-cloak class="p-4 bg-white rounded-lg md:p-8">
                    <livewire:data-tunjangan-kinerja type="masakerja" />
                </div>

                <!-- Level Unit -->
                <div x-show="activeTab === 'levelunit'" x-cloak class="p-4 bg-white rounded-lg md:p-8">
                    <livewire:data-tunjangan-kinerja type="levelunit" />
                </div>

                <!-- Proposionalitas -->
                <div x-show="activeTab === 'proposionalitas'" x-cloak class="p-4 bg-white rounded-lg md:p-8">
                    <livewire:data-tunjangan-kinerja type="proposionalitas" />
                </div>

                <!-- Point Peran Fungsional -->
                <div x-show="activeTab === 'pointperan'" x-cloak class="p-4 bg-white rounded-lg md:p-8">
                    <livewire:data-tunjangan-kinerja type="pointperan" />
                </div>
                <div x-show="activeTab === 'pointperan'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-tunjangan-kinerja type="pointperan"/>
                </div>
                <div x-show="activeTab === 'tukinjabatan'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-tunjangan-kinerja type="tukinjabatan"/>

                </div>
            </div>
        </div>
    </div>
</x-body>

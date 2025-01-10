<x-body>
    <div>
        <div class="text-5xl font-regular mb-6 text-success-600 ">Master Data Tunjangan</div>
        <div x-data="{ activeTab: 'jabatan' }" class="w-full bg-white border border-gray-200 rounded-lg shadow">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-success-300"
                role="tablist">
                <li class="me-2">
                    <button @click="activeTab = 'jabatan'" :aria-selected="activeTab === 'jabatan'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'jabatan' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Tunjangan Jabatan
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'fungsional'" :aria-selected="activeTab === 'fungsional'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'fungsional' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Tunjangan Fungsional
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'umum'" :aria-selected="activeTab === 'umum'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'umum' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Tunjangan Umum
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'khusus'" :aria-selected="activeTab === 'khusus'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'khusus' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Tunjangan Khusus
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'tidak-tetap'" :aria-selected="activeTab === 'tidak-tetap'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'tidak-tetap' ? 'bg-green-500 text-white' :
                            'hover:text-white hover:bg-success-300'">
                        Tunjangan Tidak Tetap
                    </button>
                </li>
            </ul>

            <div id="defaultTabContent">
                <div x-show="activeTab === 'jabatan'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-tunjangan type="jabatan" />
                </div>
                <div x-show="activeTab === 'fungsional'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-tunjangan type="fungsional" />
                </div>
                <div x-show="activeTab === 'umum'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    {{-- <livewire:asset-details type="umum" :aset="$aset" /> --}}
                </div>
                <div x-show="activeTab === 'khusus'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                <livewire:data-tunjangan type="khusus"/>
                </div>
                <div x-show="activeTab === 'tidak-tetap'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    {{-- <livewire:asset-details type="tidak-tetap" :aset="$aset" /> --}}
                </div>
            </div>
        </div>

        {{-- <livewire:data-jabatan /> --}}
</x-body>

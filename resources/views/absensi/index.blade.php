<x-body>
    <div>
        <div class="text-5xl font-regular mb-6 text-success-600 ">Master Absensi</div>
        <div x-data="{ activeTab: 'jadwalabsen' }" class="w-full bg-white border border-gray-200 rounded-lg shadow">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-success-300"
                role="tablist">
                <li class="me-2">
                    <button @click="activeTab = 'jadwalabsen'" :aria-selected="activeTab === 'jadwalabsen'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'jadwalabsen' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Jadwal Absensi
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'shift'" :aria-selected="activeTab === 'shift'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'shift' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Shift
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'opsi'" :aria-selected="activeTab === 'opsi'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'opsi' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Opsi
                    </button>
                </li>
                <li class="me-2">
                    <button @click="activeTab = 'status'" :aria-selected="activeTab === 'status'"
                        class="inline-block p-4 transition duration-200 focus:outline-none"
                        :class="activeTab === 'status' ? 'bg-green-500 text-white' : 'hover:text-white hover:bg-success-300'">
                        Status
                    </button>
                </li>
            </ul>
            <div id="defaultTabContent">
                <div x-show="activeTab === 'jadwalabsen'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-absensi type="jadwalabsen"/>
                </div>
                <div x-show="activeTab === 'shift'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    {{-- <livewire:asset-details type="umum" :aset="$aset" /> --}}
                </div>
                <div x-show="activeTab === 'opsi'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    {{-- <livewire:asset-details type="umum" :aset="$aset" /> --}}
                </div>
                <div x-show="activeTab === 'status'" class="p-4 bg-white rounded-lg md:p-8" role="tabpanel">
                    <livewire:data-absensi type="status"/>
                </div>
            </div>
        </div>
</x-body>
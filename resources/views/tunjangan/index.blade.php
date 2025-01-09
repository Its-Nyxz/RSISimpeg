<x-body>
    <div>
        <div class="text-5xl font-regular mb-6 text-success-600 ">Master Data Tunjangan</div>
        <div class="w-full bg-white border border-gray-200 rounded-lg shadow">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-success-3        00"
                id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                {{-- @can('jabatan_view') --}}
                <li class="me-2">
                    <button id="jabatan-tab" data-tabs-target="#jabatan" type="button" role="tab"
                        aria-controls="jabatan" aria-selected="true"
                        class="inline-block p-4 hover:text-white hover:bg-success-300 transition duration-200 ">Tunjangan
                        Jabatan</button>
                </li>
                {{-- @endcan --}}
                <li class="me-2">
                    <button id="fungsional-tab" data-tabs-target="#fungsional" type="button" role="tab"
                        aria-controls="fungsional" aria-selected="false"
                        class="inline-block p-4 hover:text-white hover:bg-success-300 transition duration-200 ">Tunjangan
                        Fungsional</button>
                </li>
                {{-- @can('trans_view') --}}
                <li class="me-2">
                    <button id="umum-tab" data-tabs-target="#umum" type="button" role="tab" aria-controls="umum"
                        aria-selected="false"
                        class="inline-block p-4 hover:text-white hover:bg-success-300 transition duration-200 ">Tunjangan
                        Umum</button>
                </li>
                {{-- @endcan --}}
                <li class="me-2">
                    <button id="khusus-tab" data-tabs-target="#khusus" type="button" role="tab"
                        aria-controls="khusus" aria-selected="false"
                        class="inline-block p-4 hover:text-white hover:bg-success-300 transition duration-200 ">Tunjangan
                        Khusus</button>
                </li>
                <li class="me-2">
                    <button id="tidak-tetap-tab" data-tabs-target="#tidak-tetap" type="button" role="tab"
                        aria-controls="tidak-tetap" aria-selected="false"
                        class="inline-block p-4 hover:text-white hover:bg-success-300 transition duration-200 ">Tunjangan
                        Tidak Tetap</button>
                </li>
            </ul>
            <div id="defaultTabContent">

                <div class="hidden p-4 bg-white rounded-lg md:p-8" id="jabatan" role="tabpanel">
                    <livewire:data-tunjangan type="jabatan" />
                </div>

                <div class="hidden p-4 bg-white rounded-lg md:p-8" id="fungsional" role="tabpanel">
                    {{-- <livewire:asset-details type="fungsional" :aset="$aset" /> --}}
                </div>
                <div class="hidden p-4 bg-white rounded-lg md:p-8" id="umum" role="tabpanel">
                    {{-- <livewire:asset-details type="umum" :aset="$aset" /> --}}
                </div>
                <div class="hidden p-4 bg-white rounded-lg md:p-8" id="khusus" role="tabpanel">
                    {{-- <livewire:asset-details type="tidak-tetap" :aset="$aset" /> --}}
                </div>
                <div class="hidden p-4 bg-white rounded-lg md:p-8" id="tidak-tetap" role="tabpanel">
                    {{-- <livewire:asset-details type="tidak-tetap" :aset="$aset" /> --}}
                </div>
            </div>

        </div>

    </div>
    {{-- <livewire:data-jabatan /> --}}
</x-body>

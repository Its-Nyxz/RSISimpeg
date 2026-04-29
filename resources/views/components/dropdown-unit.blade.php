@props(['units', 'model' => 'selectedUnit'])

@php
    // Perbaikan agar mendukung Collection (Kenaikan) maupun Array biasa (Absensi)
    $formattedUnits = collect($units)->map(function ($item, $key) {
        return is_object($item) 
            ? ['id' => $item->id ?? $key, 'nama' => $item->nama ?? $item->nama_unit ?? ''] 
            : ['id' => $key, 'nama' => $item];
    })->values()->all();
@endphp

<div class="relative w-64 max-w-full" wire:ignore x-data="{ 
    open: false,
    search: '', 
    selectedName: '-- Pilih Unit --',
    items: {{ json_encode($formattedUnits) }}
}" @click.outside="open = false; search = ''">
    
    {{-- Kotak Utama yang Menyatu dengan Pencarian --}}
    <div class="relative w-full">
        
        {{-- Mode Tertutup: Menampilkan Nama Unit Terpilih --}}
        <div x-show="!open" 
             @click="open = true; setTimeout(() => $refs.searchInput.focus(), 50)"
             class="flex items-center cursor-text rounded-lg px-4 py-2 border border-gray-300 bg-white w-full"
             style="padding-right: 20px;">
            <span x-text="selectedName" class="text-gray-900 truncate w-full"></span>
        </div>

        {{-- Mode Terbuka: Menjadi Input Pencarian --}}
        <input x-show="open" 
               x-ref="searchInput"
               type="text" 
               x-model="search" 
               placeholder="Ketik nama unit..." 
               class="w-full rounded-lg px-4 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-success-600 focus:border-success-600 text-gray-900"
               style="padding-right: 25px;"
               autocomplete="off">

        {{-- Ikon Panah --}}
        <div @click="open = !open; if(!open) search = ''" 
             class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>

    {{-- Dropdown List --}}
    <div x-show="open" 
         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 shadow-lg rounded-none overflow-hidden flex flex-col"
         style="display: none;">
        
        <div class="overflow-y-auto max-h-60 py-1">
            <div @click="
                    selectedName = '-- Pilih Unit --'; 
                    $wire.set('{{ $model }}', ''); 
                    open = false; 
                    search = '';
                 " 
                 class="w-full px-3 py-2 text-[14px] text-gray-800 hover:bg-blue-600 hover:text-white cursor-pointer truncate">
                <span>-- Pilih Unit --</span>
            </div>

            <template x-for="item in items.filter(i => i.nama.toLowerCase().includes(search.toLowerCase()))" :key="item.id">
                <div @click="
                        selectedName = item.nama; 
                        $wire.set('{{ $model }}', item.id); 
                        open = false; 
                        search = '';
                     " 
                     class="w-full px-3 py-2 text-[14px] text-gray-800 hover:bg-blue-600 hover:text-white cursor-pointer truncate">
                    <span x-text="item.nama"></span>
                </div>
            </template>
            
            <div x-show="items.filter(i => i.nama.toLowerCase().includes(search.toLowerCase())).length === 0" 
                 class="w-full px-3 py-2 text-[14px] text-gray-500 italic">
                Unit tidak ditemukan...
            </div>
        </div>
    </div>
</div>

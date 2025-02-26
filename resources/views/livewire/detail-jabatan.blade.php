<div>
    <div class="flex justify-end items-center mb-5 gap-2">
        <a href="/jabatanperizinan"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            Kembali
        </a>
        <button class="bg-white border-2 border-gray-300 w-10 h-10 flex items-center justify-center cursor-pointer">
            <img src="https://cdn-icons-png.flaticon.com/512/484/484611.png" alt="Trash Icon" class="w-5 h-5">
        </button>
    </div>

    @if ($roleId)
        <x-card title="Perizinan">
            @if (session()->has('message'))
                <div class="text-green-500 font-semibold">{{ session('message') }}</div>
            @endif
            <div class="space-y-4">
                @foreach ($permissions as $category => $actions)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 border-b pb-2">{{ $category }}</h4>
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="selectAllForCategory('{{ $category }}')" class="text-sm text-primary-600 hover:underline">
                                Pilih Semua
                            </button>
                            @if ($this->isCategoryFullySelected($category))
                                <button type="button" wire:click="resetAllForCategory('{{ $category }}')" class="text-sm text-red-600 hover:underline">
                                    Reset
                                </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @foreach ($actions as $action)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                    id="{{ $action }}" 
                                    wire:model.live="selectedPermissions" 
                                    value="{{ $action }}" 
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <label for="{{ $action }}" class="text-sm text-gray-600">
                                        {{ Str::ucfirst(str_replace('_', ' ', $action)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    @else
        <div class="text-red-500 font-semibold">Role tidak ditemukan atau sudah dihapus.</div>
    @endif
</div>

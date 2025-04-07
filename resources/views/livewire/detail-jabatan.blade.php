<div>
    <div class="flex justify-end items-center mb-5 gap-2">
        <a href="/jabatanperizinan"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            Kembali
        </a>
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
                            <button type="button" wire:click="selectAllForCategory('{{ $category }}')"
                                class="text-sm text-primary-600 hover:underline">
                                Pilih Semua
                            </button>
                            @if ($this->isCategoryFullySelected($category))
                                <button wire:click="resetAllForCategory('{{ $category }}')"
                                    class="text-sm text-red-600 hover:underline">
                                    Reset
                                </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @foreach ($actions as $key => $value)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="{{ is_array($value) ? $key : $value }}"
                                        wire:model.live="selectedPermissions"
                                        value="{{ is_array($value) ? $key : $value }}"
                                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <label for="{{ is_array($value) ? $key : $value }}" class="text-sm text-gray-600">
                                        {{ is_array($value) ? $value : Str::title(str_replace('-', ' ', $value)) }}
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

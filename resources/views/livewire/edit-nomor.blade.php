<div class="space-y-6">
    <!-- Title -->
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-700">Edit Nomor WhatsApp</h1>
        <a href="{{ url()->previous() }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <!-- Form -->
    <form wire:submit.prevent="updateNomor" class="space-y-4">
        <!-- Input Section -->
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-lg p-6 space-y-4">
            <p class="text-green-700 font-medium">Silahkan Masukkan Nomor WhatsApp Anda yang Baru:</p>

            <!-- Old WhatsApp Number -->
            <div class="grid grid-cols-2 items-center">
                <p class="text-green-700 font-medium">Nomor WhatsApp Lama:</p>
                <span class="font-bold text-center w-full max-w-xs text-green-800">{{ $old_no_hp ?? '-' }}</span>
            </div>

            <!-- New WhatsApp Number -->
            <div class="grid grid-cols-2 gap-4 items-center">
                <label for="no_hp" class="text-green-700 font-medium">Nomor WhatsApp Baru:</label>
                <input type="text" id="no_hp" wire:model.live="no_hp"
                    class="w-full max-w-xs rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
            </div>

            <!-- Error Message -->
            @error('no_hp')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notification -->
    @if (session()->has('success'))
        <div class="mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

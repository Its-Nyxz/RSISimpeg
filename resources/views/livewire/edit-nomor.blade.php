<div>
    <h1 class="text-2xl font-bold text-success-900" style="margin-bottom: 20px;">Edit Nomor Whatsapp</h1>
    <form wire:submit.prevent="updateNomor">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <p class="text-green-700 font-medium" style="margin-bottom: 20px;">Silahkan Masukan Nomor WhatsApp Anda yang Baru</p>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <!-- Nomor WhatsApp Lama -->
                    <p class="text-green-700 font-medium">Nomor WhatsApp Lama:</p>
                    <span class="font-bold text-center" style="margin-right: 900px; color: #006633;">{{ $old_no_hp ?? '-' }}</span>
                    
                    <!-- Nomor WhatsApp Baru -->
                    <label for="no_hp" class="text-sm font-medium text-green-700">Nomor WhatsApp Baru</label>
                    <input type="text" id="no_hp" wire:model="no_hp" 
                        class="form-control @error('no_hp') is-invalid @enderror w-full max-w-xs rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                </div>                
                @error('no_hp')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
                
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

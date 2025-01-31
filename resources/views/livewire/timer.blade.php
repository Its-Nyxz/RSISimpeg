<div>
    <h1 class="text-2xl font-bold text-success-900">Timer</h1>

    <div class="p-4 text-left">
        <p class="text-gray-500">{{ now()->format('l, d F Y') }}</p>
        <h1 class="text-4xl font-bold text-center">{{ gmdate("H:i:s", $time) }}</h1>
    
        <div class="mt-4 text-center" style="margin-bottom: 20px;">
            <button 
                wire:click="startTimer" 
                class="px-4 py-2 rounded text-white {{ $isRunning ? 'bg-gray-500' : 'bg-green-800' }}"
                {{ $isRunning ? 'disabled' : '' }}
            >Mulai Bekerja</button>

            <button 
                wire:click="pauseTimer" 
                class="bg-green-800 text-white px-4 py-2 rounded {{ !$isRunning || $isPaused ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ !$isRunning || $isPaused ? 'disabled' : '' }}
            >Istirahat</button>

            <button 
                wire:click="resumeTimer" 
                class="bg-green-800 text-white px-4 py-2 rounded {{ !$isPaused ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ !$isPaused ? 'disabled' : '' }}
            >Kembali Bekerja</button>

            <button 
                wire:click="stopTimer" 
                class="bg-red-800 text-white px-4 py-2 rounded {{ !$isRunning ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ !$isRunning ? 'disabled' : '' }}
            >Selesai Bekerja</button>
        </div>
    
        <script>
            setInterval(() => @this.call('updateTimer'), 1000);
        </script>
    
        <h3 class="font-bold mt-4">RENCANA KERJA</h3>
        <p class="text-gray-600">Pengecekan senad, optimasi master agent dari voas ke senad, lanjut rombak database RSI</p>
    </div>    
</div>
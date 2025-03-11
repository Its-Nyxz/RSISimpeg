<div wire:poll.1000ms="updateTimer">
    <h1 class="text-2xl font-bold text-success-900">Timer</h1>
    <p class="text-gray-500">{{ now()->format('l, d F Y') }}</p>
    <h1 class="text-4xl font-bold text-center">{{ gmdate('H:i:s', $time) }}</h1>

    <div class="mt-4 text-center" style="margin-bottom: 20px;">
        <button wire:click="openWorkPlanModal"
            class="px-4 py-2 rounded text-white {{ $isRunning ? 'bg-gray-500' : 'bg-green-800' }}"
            {{ $isRunning ? 'disabled' : '' }}>
            Mulai Bekerja
        </button>

        <button wire:click="pauseTimer"
            class="bg-green-800 text-white px-4 py-2 rounded {{ !$isRunning || $isPaused ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$isRunning || $isPaused ? 'disabled' : '' }}>
            Istirahat
        </button>

        <button wire:click="resumeTimer"
            class="bg-green-800 text-white px-4 py-2 rounded {{ !$isPaused ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$isPaused ? 'disabled' : '' }}>
            Kembali Bekerja
        </button>

        <button wire:click="openWorkReportModal"
            class="bg-red-800 text-white px-4 py-2 rounded {{ !$isRunning ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$isRunning ? 'disabled' : '' }}>
            Selesai Bekerja
        </button>
    </div>

    <div class="mt-4 text-left" style="margin-top: 50px;">
        <h1 class="font-bold mt-4">RENCANA KERJA</h1>
        @forelse ($items as $item)
            <p class="text-gray-600">{{ $item['rencana_kerja'] }}</p>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">Tidak rencana kerja.</td>
            </tr>
        @endforelse
    </div>

    <!-- Modal Rencana Kerja -->
    @if ($showWorkPlanModal)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Rencana Kerja</h2>
            <textarea wire:model="workPlan" rows="4" class="w-full p-2 border rounded-lg"></textarea>
            <div class="mt-4 flex justify-end">
                <button wire:click="startTimerWithPlan" class="bg-green-800 text-white px-4 py-2 rounded-lg mr-2">
                    Mulai
                </button>
                <button wire:click="$set('showWorkPlanModal', false)" class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Laporan Kerja -->
    @if ($showWorkReportModal)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Laporan Kerja</h2>
            <textarea wire:model="workReport" rows="4" class="w-full p-2 border rounded-lg"></textarea>
            <div class="mt-4 flex justify-end">
                <button wire:click="submitWorkReport" class="bg-green-800 text-white px-4 py-2 rounded-lg mr-2">
                    Simpan
                </button>
                <button wire:click="$set('showWorkReportModal', false)" class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
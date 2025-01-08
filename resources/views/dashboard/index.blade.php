<x-body>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-card title="Total Karyawan" class="mb-2">
            <div class="text-3xl font-semibold">{{ fake()->paragraph(5) }}</div>
        </x-card>
        <x-card title="Karyawan Hadir" class="mb-2">
            <div class="text-3xl font-semibold">{{ fake()->paragraph(5) }}</div>
        </x-card>
        <x-card title="Karyawan Pulang" class="mb-2">
            <div class="text-3xl font-semibold">{{ fake()->paragraph(5) }}</div>
        </x-card>
    </div>
    <x-card title="Approval Cuti" class="mb-2">
        <div class="text-3xl font-semibold">{{ fake()->paragraph(10) }}</div>
    </x-card>
</x-body>

<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-success-900 ">Edit User {{ $user->name ?? '-' }}</h1>
        <div>
            <a href="{{ route('userprofile.index') }}"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali </a>
        </div>
    </div>
    <livewire:edit-users :user="$user" />
</x-body>

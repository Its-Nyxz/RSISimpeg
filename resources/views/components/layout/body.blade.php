<!DOCTYPE html>
<html lang="en">
<x-head />


<body class="min-h-screen bg-no-repeat bg-center bg-cover"
    style="background-image: url('{{ asset('img/bg-login.png') }}');">
    {{-- <x-navbar />
    <x-sidebar /> --}}
    <livewire:navbar />
    <livewire:sidebar />
    <div class="relative min-h-screen px-4 pt-24 pb-10 sm:px-6 sm:ps-[18rem] max-w-full text-gray-800">
        {{ $slot }}
    </div>
    @stack('html')
</body>

@if (session('success'))
    <script type="module">
        feedback('Berhasil', "{{ session('success') }}", 'success');
    </script>
@endif
@if (session('error'))
    <script type="module">
        feedback('Gagal', "{{ session('error') }}", 'error');
    </script>
@endif
@stack('scripts')

</html>
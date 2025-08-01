<!DOCTYPE html>
<html lang="en">
<x-head />


<body class="min-h-screen bg-no-repeat bg-center bg-cover"
    style="background-image: url('{{ asset('img/bg-login.png') }}');">
    {{-- <x-navbar />
    <x-sidebar /> --}}
    <livewire:navbar />
    <livewire:sidebar />
    <div class="container ps-10 pt-[7rem] sm:ps-[20rem] max-w-[90%] sm:max-w-[96%] md:max-w-[98%] text-gray-800">
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

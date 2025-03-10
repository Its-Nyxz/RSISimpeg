<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-success-900">Import Gaji</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" placeholder="Cari Data Gaji Karyawan..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>

            <!-- Tombol Import Data Absensi -->
            <a href="#"
                class="text-white bg-[#006633] hover:bg-[#007A4D] font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fa-solid fa-file-import" style="color: #ffffff;"></i> Import Data Absensi
            </a>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-sm uppercase bg-success-400 text-success-900">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Karyawan</th>
                    <th scope="col" class="px-6 py-3">Jabatan</th>
                    <th scope="col" class="px-6 py-3">Pendidikan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                    <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">{{  $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->kategorijabatan->nama ?? '-'}}</td>
                    <td class="px-6 py-4"> {{ $user->pendidikan->nama ?? '-' }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-6 py-4">Tidak ada data Karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
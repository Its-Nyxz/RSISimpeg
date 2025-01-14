<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <h1 class="text-2xl font-bold text-success-900">Kenaikan Berkala & Golongan</h1>
            <!-- 
                <div class="flex justify-between items-center gap-4 mb-3">
                    <div class="flex-1">
                        <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Kenaikan Berkala & Golongan..."
                            class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
                    </div>
    
                    <a href="#"
                        class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        + Tambah Kenaikan Berkala & Golongan
                    </a>
                </div>
            -->
                
                <table class="w-full text-sm text-left text-gray-700" style="margin-top: 20px;">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th scope="col" class="px-6 py-3" rowspan="2">No</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">Nama</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">Pendidikan</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">TMT</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">Tahun</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">Bulan</th>
                            <th scope="col" class="px-6 py-3" rowspan="2">Gaji Sekarang</th>
                            <th scope="col" class="px-6 py-3" colspan="2">Kenaikan Gaji Berkala</th>
                            <th scope="col" class="px-6 py-3" colspan="2">Kenaikan Golongan</th>
                        </tr>
                        <tr>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">Gaji</th>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">Gaji</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($data as $item)
                            <tr
                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    {{ $item['nama'] }}
                                </td>
                                <td class="px-6 py-4">{{ $item['pendidikan'] }}</td>
                                <td class="px-6 py-4">{{ $item['tmt'] }}</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">{{ $item['gaji_sekarang'] }}</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
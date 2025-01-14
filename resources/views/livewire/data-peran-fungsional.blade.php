<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <h1 class="text-2xl font-bold text-success-900">Peran Fungsional</h1>
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
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Unit</th>
                            <th scope="col" class="px-6 py-3">Peran Pokok</th>
                            <th scope="col" class="px-6 py-3">Pelatihan</th>
                            <th scope="col" class="px-6 py-3">Keterlibatan Tim</th>
                            <th scope="col" class="px-6 py-3">Posisi Tim</th>
                            <th scope="col" class="px-6 py-3">Shift</th>
                            <th scope="col" class="px-6 py-3">PK</th>
                            <th scope="col" class="px-6 py-3">Profit Centre / Cost Centre</th>
                            <th scope="col" class="px-6 py-3">Total Peran Fungsional</th>
                            <th scope="col" class="px-6 py-3">Rincian Tim</th>
                            <th scope="col" class="px-6 py-3">Sertifikat Tim</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($data as $item)
                            <tr class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    {{ $item['nama'] }}
                                </td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">{{ $item['shift'] }}</td>
                                <td class="px-6 py-4">-</td>
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
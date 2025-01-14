<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <!--<h1 class="text-2xl font-bold text-success-900">Pemberitahuan Kenaikan Berkala & Golongan</h1> -->
                <table class="w-full text-sm text-center text-gray-700" style="margin-top: 20px;">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th scope="col" class="px-6 py-3">Unit</th>
                            <th scope="col" class="px-2 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Masa Kerja</th>
                            <th scope="col" class="px-6 py-3">Peran Fungsional</th>
                            <th scope="col" class="px-6 py-3">Kinerja</th>
                            <th scope="col" class="px-6 py-3">Unit Kerja</th>
                            <th scope="col" class="px-6 py-3">Range Jam Kerja</th>
                            <th scope="col" class="px-6 py-3">Sub Total Poin</th>
                            <th scope="col" class="px-6 py-3">Jabatan</th>
                            <th scope="col" class="px-6 py-3">Proporsionalitas</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                            <th scope="col" class="px-6 py-3">Lembur</th>
                            <th scope="col" class="px-6 py-3">Total Poin</th>
                            <th scope="col" class="px-6 py-3">Nilai Poin</th>
                            <th scope="col" class="px-6 py-3">Tunjangan Kinerja</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($data as $index => $item)
                            <tr
                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td scope="row" class="px-6 py-4 font-medium text-success-900 whitespace-nowrap">
                                    -
                                </td>
                                <td class="px-6 py-4">{{ $index+1 }}</td>
                                <td class="px-6 py-4">{{ $item['name'] }}</td>
                                <td class="px-6 py-4">{{ $item['masa_kerja']}}</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">{{ $item['range_jam_kerja']}}</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">{{ $item['jabatan']}}</td>
                                <td class="px-6 py-4">-</td>
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
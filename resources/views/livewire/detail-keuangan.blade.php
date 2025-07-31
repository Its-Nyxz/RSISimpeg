<div>
<div class="flex items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-success-900">Keuangan</h1>
        <p class="text-base text-success-900 flex items-center">
            <i class="fa-solid fa-caret-right mr-2"></i> Data Karyawan
        </p>
        <p class="text-base text-success-900 flex items-center">
            <i class="fa-solid fa-caret-right mr-2"></i> Detail Data Karyawan
        </p>
    </div>
    <div class="flex justify-end">
        <a href="#" class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            <i class="fa-solid fa-chart-pie"></i> Add Potongan
        </a>
    </div>
</div>


    <!-- Slip Gaji dalam Card -->
    <x-card title="MD-110" class="mb-6 text-success-900">
        <!-- Header -->
        <div class="text-center text-green-900 font-bold text-lg mb-4">
            SLIP PENERIMAAN GAJI KARYAWAN TETAP
        </div>

        <!-- Informasi Karyawan -->
        <div class="flex flex-col items-center gap-2 mb-6">
            <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>No Urut</strong>
                    <span>{{ $user->id ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Nama</strong>
                    <span>{{ $user->name ?? '-' }}</span>
                </div>
        </div>

        <hr class="border-green-700 mb-6 w-1/2 mx-auto">

        <!-- Bagian Potongan -->
        <div class="text-center">
            <div class="space-y-2">
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Potongan-Potongan</strong>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Simpanan Wajib</strong>
                    <span>{{ $potonganData->simpanan_wajib ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Simpanan Pokok</strong>
                    <span>{{ $potonganData->simpanan_pokok ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>IBI</strong>
                    <span>{{ $potonganData->ibi ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>IDI</strong>
                    <span>{{ $potonganData->idi ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>PPNI</strong>
                    <span>{{ $potonganData->ppni ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Pinjaman Koperasi</strong>
                    <span>{{ $potonganData->pinjam_kop ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Obat</strong>
                    <span>{{ $potonganData->obat ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Angsuran Bank</strong>
                    <span>{{ $potonganData->a_b ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Angsuran Perum</strong>
                    <span>{{ $potonganData->a_p ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Dansos</strong>
                    <span>{{ $potonganData->dansos ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>DPLK</strong>
                    <span>{{ $potonganData->dplk ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>BPJS Tenaga Kerja</strong>
                    <span>{{ $potonganData->bpjs_tk ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>BPJS Kesehatan</strong>
                    <span>{{ $potonganData->bpjs_kes ?? '-' }}</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Rekonsiliasi BPJS Kesehatan</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>BPJS Kesehatan Ortu/Tambahan</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>PPH 21</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Kurangan PPH 21 Tahun 2000?</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Angsuran Kurban</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Amaliah Ramadhan</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Ranap</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Potongan Selisih</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Iuran Perkasi</strong>
                    <span>-</span>
                </div>
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Lain-lain</strong>
                    <span>-</span>
                </div>
                <hr class="border-green-700 mb-6 w-1/2 mx-auto">
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Jumlah Potongan</strong>
                    <span>-</span>
                </div>
                <hr class="border-green-700 mb-6 w-1/2 mx-auto">
                <div class="flex justify-between w-full max-w-md mx-auto">
                    <strong>Gaji Netto</strong>
                    <span>-</span>
                </div>
            </div>
        </div>
    </x-card>
</div>

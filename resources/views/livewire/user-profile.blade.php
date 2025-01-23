<div>
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; align-items: center;">
            <i class="fa-solid fa-gear" style="font-size: 28px; margin-right: 10px;"></i>
            <p style="font-family: 'Gilroy-Bold', sans-serif; font-size: 28px; font-weight: bold; margin: 0;">
                Settings
            </p>
        </div>
        <!-- Kotak No. KTP -->
        <div style="
            background-color: #28a745;
            color: white;
            font-family: 'Gilroy-Bold', sans-serif;
            font-size: 14px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
        ">
            {{ $userprofile->no_ktp ?? '-' }}
        </div>
    </div>
    <div class="flex space-x-6">
        <div class="w-1/2">
            <x-card :title="'Profile'" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div style="display: grid; grid-template-columns: auto 1fr; row-gap: 10px; column-gap: 50px;">
                        <div><strong>Nama</strong></div>
                        <div>: {{ $userprofile->name }}</div>
                        
                        <div><strong>Jabatan</strong></div>
                        <div>: {{ $userprofile->jabatan->nama ?? '-' }}</div>
                        
                        <div><strong>Tempat Tanggal Lahir</strong></div>
                        <div>: {{ $userprofile->tempat }} - {{ $userprofile->tanggal_lahir }}</div>
                        
                        <div><strong>Tanggal Tetap</strong></div>
                        <div>: {{ $userprofile->tanggal_tetap ?? '-' }}</div>
                        
                        <div><strong>Pendidikan Awal</strong></div>
                        <div>: {{ $userprofile->pendidikan_awal ?? '-' }}</div>
                        
                        <div><strong>Pendidikan Penyesuaian</strong></div>
                        <div>: {{ $userprofile->pendidikan_penyesuaian ?? '-' }}</div>
                        
                        <div><strong>Tanggal Penyesuaian</strong></div>
                        <div>: {{ $userprofile->tgl_penyesuaian ?? '-' }}</div>
                        
                        <div><strong>Informasi Pensiun</strong></div>
                        <div>: {{ $userprofile->pensiun ?? '-' }}</div>
                    </div>
                </div>
                <!-- Tombol Edit Profile -->
                <div style="margin-top: 20px;">
                    <a href="{{ route('profile.edit') }}" class="btn btn-success" style="
                        font-family: 'Gilroy-Bold', sans-serif;
                        font-size: 14px;
                        font-weight: bold;
                        background-color: #28a745;
                        color: white;
                        padding: 10px 20px;
                        border-radius: 5px;
                        text-decoration: none;
                    ">
                        Edit Profile
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>

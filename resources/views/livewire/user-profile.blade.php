<style>
    .icon {
        margin-left: 8px; /* Tambahkan jarak antara teks dan ikon */
        cursor: pointer;
        position: relative;
    }

    .icon:hover .tooltip {
        display: block; /* Tampilkan tooltip saat hover */
    }

    .tooltip {
        display: none;
        position: absolute;
        top: -30px; /* Posisi tooltip di atas ikon */
        left: 0;
        background-color: #006633;
        color: white;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .tooltip:after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 10px;
        border-width: 5px;
        border-style: solid;
        border-color: #006633 transparent transparent transparent; /* Segitiga tooltip */
    }
</style>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; align-items: center;">
            <i class="fa-solid fa-gear" style="font-size: 28px; margin-right: 10px;"></i>
            <p style="font-family: 'Gilroy-Bold', sans-serif; font-size: 28px; font-weight: bold; margin: 0;">
                Settings
            </p>
        </div>

        <div style="
            background-color: #5FB88B;

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

            {{ $user->id ?? '-' }}
        </div>
    </div>
            {{ $userprofile->no_ktp ?? '-' }}
        </div>
    </div>

    <div class="flex space-x-6">
        <div class="w-1/2">
            <x-card :title="'Profile'" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div style="display: grid; grid-template-columns: auto 1fr; row-gap: 10px; column-gap: 50px;">
                        <div><strong>Nama</strong></div>

                        <div>: {{ $user->name }}</div>
                        
                        <div><strong>Jabatan</strong></div>
                        <div>: {{ $user->jabatan->nama ?? '-' }}</div>
                        
                        <div><strong>Tempat Tanggal Lahir</strong></div>
                        <div>: {{ $user->tempat }} - {{ $user->tanggal_lahir }}</div>
                        
                        <div><strong>Tanggal Tetap</strong></div>
                        <div>: {{ $user->tanggal_tetap ?? '-' }}</div>
                        
                        <div><strong>Pendidikan Awal</strong></div>
                        <div>: {{ $user->pendidikan_awal ?? '-' }}</div>
                        
                        <div><strong>Pendidikan Penyesuaian</strong></div>
                        <div>: {{ $user->pendidikan_penyesuaian ?? '-' }}</div>
                        
                        <div><strong>Tanggal Penyesuaian</strong></div>
                        <div>: {{ $user->tgl_penyesuaian ?? '-' }}</div>
                        
                        <div><strong>Informasi Pensiun</strong></div>
                        <div>: {{ $user->pensiun ?? '-' }}</div>
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

    <x-card :title="'Login dan Keamanan'" class="max-w-md">
        <p>
            <strong>ID:</strong> {{ $user->id }}
        </p>
        <p>
            <strong>No. WhatsApp:</strong> 
            @empty($user->no_hp)
                -
            @else
                {{ $user->no_hp }}
            @endempty
            <span class="icon">
                <i class="fa-solid fa-pen" style="color: #006633;"></i>
                <span class="tooltip">Ganti No WhatsApp</span>
            </span>
        </p>
        <p>
            <strong>Email:</strong> 
            @empty($user->email)
                -
            @else
                {{ $user->email }}
            @endempty
            <span class="icon">
                <i class="fa-solid fa-pen" style="color: #006633;"></i>
                <span class="tooltip">Ganti Email</span>
            </span>
        </p>
        <p>
            <strong>Password:</strong> ************
            <span class="icon">
                <i class="fa-solid fa-pen" style="color: #006633;"></i>
                <span class="tooltip">Ganti Password</span>
            </span>
        </p>
    </x-card>
</div>


<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DetailJabatan extends Component
{
    public $roleId;
    public $formattedRole;
    public $permissions = [];
    public $selectedPermissions = [];

    protected $defaultPermissions = [
        'Aktivitas Kerja' => [
            'timer',
            'list-history',
            'list-history-user',
            'list-history-edit',
            'list-history-create',
            'absen',
            'template-jadwal',
            'import-jadwal',
            'tambah-jadwal',
            'edit-jadwal',
        ],
        'Master Data' => ['master-data', 'tunjangan', 'golongan', 'gaji-pokok', 'pendidikan', 'unit-kerja', 'potongan', 'tunjangan-kinerja', 'kategori-jabatan'],
        'Kepegawaian' => ['create-data-karyawan', 'detail-data-karyawan', 'edit-data-karyawan', 'tambah-history', 'view-kepegawaian', 'view-kenaikan', 'notification-cuti', 'view-import-gaji', 'view-poin-peran', 'view-poin-penilaian'],
        'Keuangan' => ['view-keuangan'],
        'Pengaturan' => ['hak-akses'],
    ];


    public function mount($roleId)
    {
        $this->roleId = $roleId;
        $role = Role::findOrFail($this->roleId);
        $this->formattedRole = $role->name;

        // Ambil semua permission yang ada di database
        $this->permissions = $this->defaultPermissions;

        // Jika role belum punya permission, kasih semua default permission
        $existingPermissions = $role->permissions->pluck('name')->toArray();
        if (empty($existingPermissions)) {
            $this->selectedPermissions = collect($this->defaultPermissions)->flatten()->toArray();
            $this->syncPermissionsToRole(); // Simpan langsung ke DB
        } else {
            $this->selectedPermissions = $existingPermissions;
        }
    }

    public function updatedSelectedPermissions()
    {
        $this->syncPermissionsToRole();
    }

    public function selectAllForCategory($category)
    {
        if (isset($this->permissions[$category])) {
            foreach ($this->permissions[$category] as $key => $value) {
                $permission = is_array($value) ? $key : $value; // Menyesuaikan format array
                if (!in_array($permission, $this->selectedPermissions)) {
                    $this->selectedPermissions[] = $permission;
                }
            }
            $this->syncPermissionsToRole();
        }
    }

    public function resetAllForCategory($category)
    {
        if (isset($this->permissions[$category])) {
            foreach ($this->permissions[$category] as $key => $value) {
                $permission = is_array($value) ? $key : $value;
                $this->selectedPermissions = array_diff($this->selectedPermissions, [$permission]);
            }
            $this->syncPermissionsToRole();
        }
    }

    public function isCategoryFullySelected($category)
    {
        return isset($this->permissions[$category]) &&
            !array_diff(array_keys($this->permissions[$category]), $this->selectedPermissions);
    }

    public function syncPermissionsToRole()
    {
        $role = Role::findOrFail($this->roleId);
        $role->syncPermissions($this->selectedPermissions);
    }

    public function render()
    {
        return view('livewire.detail-jabatan');
    }
}

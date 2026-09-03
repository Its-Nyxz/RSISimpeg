<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JenisFile;
use App\Models\SourceFile;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadUserProfile extends Component
{
    use WithFileUploads;

    public $jenis_file_id;
    public $file;
    public $mulai;
    public $selesai;
    public $jenisFiles;
    public $isSipStr = false;
    public $pelatihan;
    public $jumlah_jam;


    public function mount()
    {
        $this->jenisFiles = JenisFile::all();
    }

    public function updatedJenisFileId()
    {
        $jenis = JenisFile::find($this->jenis_file_id);

        $this->isSipStr = $jenis && (
            str_contains(strtolower($jenis->name), 'sip') ||
            str_contains(strtolower($jenis->name), 'str')
        );

        // Menentukan apakah jenis file adalah Sertifikat Pelatihan
        $this->pelatihan = $jenis &&
            str_contains(strtolower($jenis->name), 'sertifikat pelatihan');
    }

    public function save()
    {
        $this->validate([
            'jenis_file_id' => 'required|exists:jenis_files,id',
            'file' => 'required|file|max:5120',
            'mulai' => $this->isSipStr ? 'required|date' : 'nullable',
            'selesai' => $this->isSipStr ? 'required|date|after_or_equal:mulai' : 'nullable',
            'jumlah_jam' => $this->pelatihan ? 'required|integer' : 'nullable|integer',
        ]);


        $jenisFile = JenisFile::find($this->jenis_file_id);

        if ($jenisFile) {

            $namaJenisFile = strtolower(trim($jenisFile->name));

            $dokumenTerbatas = [
                'ktp',
                'pas foto',
                'kk',
            ];

            if (in_array($namaJenisFile, $dokumenTerbatas)) {

                $sudahAda = SourceFile::where('user_id', Auth::id())
                    ->where('jenis_file_id', $this->jenis_file_id)
                    ->exists();

                if ($sudahAda) {
                    session()->flash(
                        'error',
                        $jenisFile->name . ' sudah pernah di-upload. Silakan hapus file lama terlebih dahulu jika ingin menggantinya.'
                    );

                    return;
                }
            }
        }


        if ($this->pelatihan && !$this->mulai && !$this->selesai) {
            if (!$this->jumlah_jam) {
                session()->flash(
                    'error',
                    'Jumlah jam harus diisi jika tidak ada tanggal mulai dan selesai.'
                );

                return;
            }
        }


        $path = $this->file->store('dokumen', 'public');

        $userName = Auth::user()->name;

        $jenisFileName = JenisFile::find($this->jenis_file_id)?->name ?? 'Dokumen';

        $newFileName = $userName . ' - ' .
            $jenisFileName . '.' .
            $this->file->getClientOriginalExtension();


        SourceFile::create([
            'user_id' => Auth::id(),
            'jenis_file_id' => $this->jenis_file_id,
            'path' => $path,
            'name' => $newFileName,
            'fileable_id' => Auth::id(),
            'fileable_type' => Auth::user()::class,
            'mulai' => $this->mulai,
            'selesai' => $this->selesai,
            'jumlah_jam' => $this->jumlah_jam,
        ]);


        session()->flash('success', 'File berhasil diupload.');

        $this->reset([
            'file',
            'jenis_file_id',
            'mulai',
            'selesai',
            'isSipStr',
            'jumlah_jam'
        ]);
    }


    public function deleteFile($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$file) {
            session()->flash(
                'error',
                'Dokumen tidak ditemukan atau Anda tidak memiliki akses.'
            );

            return;
        }

        
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }


        // Hapus data dari database
        $file->delete();


        session()->flash(
            'success',
            'Dokumen berhasil dihapus.'
        );
    }


    public function render()
    {
        $uploadedFiles = SourceFile::where('user_id', Auth::id())->get();

        return view('livewire.upload-user-profile', [
            'uploadedFiles' => $uploadedFiles,
        ]);
    }
}
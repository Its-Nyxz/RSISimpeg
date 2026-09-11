<?php

namespace App\Livewire;

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
    public $pelatihan = false;
    public $jumlah_jam;

    public function mount()
    {
        $this->jenisFiles = JenisFile::all();
    }

    public function updatedJenisFileId()
    {
        $jenis = JenisFile::find($this->jenis_file_id);

        $this->isSipStr = false;
        $this->pelatihan = false;

        if (!$jenis) {
            return;
        }

        $namaJenis = strtolower(trim($jenis->name));

        $this->isSipStr =
            str_contains($namaJenis, 'sip') ||
            str_contains($namaJenis, 'str');

        $this->pelatihan =
            str_contains($namaJenis, 'sertifikat pelatihan');
    }

    public function save()
    {
        $this->validate([
            'jenis_file_id' => 'required|exists:jenis_files,id',
            'file' => 'required|file|max:2048',

            'mulai' => $this->isSipStr
                ? 'required|date'
                : 'nullable',

            'selesai' => $this->isSipStr
                ? 'required|date|after_or_equal:mulai'
                : 'nullable',

            'jumlah_jam' => $this->pelatihan
                ? 'required|integer'
                : 'nullable|integer',
        ]);

        $jenisFile = JenisFile::find($this->jenis_file_id);

        if ($jenisFile) {

            $namaJenisFile = strtolower(trim($jenisFile->name));

            $isDokumenTerbatas =
                str_contains($namaJenisFile, 'id/ktp') ||
                str_contains($namaJenisFile, 'pas foto') ||
                str_contains($namaJenisFile, 'kartu keluarga');

            if ($isDokumenTerbatas) {

                $sudahAda = SourceFile::where('user_id', Auth::id())
                    ->where('jenis_file_id', $this->jenis_file_id)
                    ->exists();

                if ($sudahAda) {

                    $this->dispatch(
                        'feedback',
                        title: 'Upload Gagal',
                        message: $jenisFile->name .
                            ' sudah pernah di-upload. Silakan hapus file lama terlebih dahulu jika ingin menggantinya.',
                        icon: 'error'
                    );

                    return;
                }
            }
        }

        if (
            $this->pelatihan &&
            !$this->mulai &&
            !$this->selesai &&
            !$this->jumlah_jam
        ) {

            $this->dispatch(
                'feedback',
                title: 'Upload Gagal',
                message: 'Jumlah jam harus diisi jika tidak ada tanggal mulai dan selesai.',
                icon: 'error'
            );

            return;
        }

        $path = $this->file->store(
            'dokumen',
            'public'
        );

        $userName = Auth::user()->name;

        $jenisFileName = $jenisFile?->name ?? 'Dokumen';

        $newFileName =
            $userName .
            ' - ' .
            $jenisFileName .
            '.' .
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

        $this->dispatch(
            'feedback',
            title: 'Berhasil',
            message: 'File berhasil diupload.',
            icon: 'success'
        );

        $this->reset([
            'file',
            'jenis_file_id',
            'mulai',
            'selesai',
            'jumlah_jam',
        ]);

        $this->isSipStr = false;
        $this->pelatihan = false;
        $this->resetErrorBag();
    }

    public function downloadFile($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$file || !$file->path) {

            $this->dispatch(
                'feedback',
                title: 'Download Gagal',
                message: 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.',
                icon: 'error'
            );

            return;
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($file->path)) {

            $this->dispatch(
                'feedback',
                title: 'Download Gagal',
                message: 'File tidak ditemukan di penyimpanan.',
                icon: 'error'
            );

            return;
        }

        $fullPath = $disk->path($file->path);

        $downloadName = str_replace(['/', '\\'], '-', $file->name);

        return response()->download(
            $fullPath,
            $downloadName
        );
    }

    public function deleteFile($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$file) {

            $this->dispatch(
                'feedback',
                title: 'Gagal',
                message: 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.',
                icon: 'error'
            );

            return;
        }

        if ($file->path) {

            $disk = Storage::disk('public');

            if ($disk->exists($file->path)) {
                $disk->delete($file->path);
            }
        }

        $file->delete();

        $this->reset([
            'file',
            'jenis_file_id',
            'mulai',
            'selesai',
            'jumlah_jam',
        ]);

        $this->isSipStr = false;
        $this->pelatihan = false;
        $this->resetErrorBag();

        $this->dispatch(
            'feedback',
            title: 'Berhasil',
            message: 'Dokumen berhasil dihapus.',
            icon: 'success'
        );
    }

    public function render()
    {
        $uploadedFiles = SourceFile::where(
            'user_id',
            Auth::id()
        )
            ->with('jenisFile')
            ->get();

        return view(
            'livewire.upload-user-profile',
            [
                'uploadedFiles' => $uploadedFiles,
            ]
        );
    }
}
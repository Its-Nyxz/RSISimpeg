<?php

namespace App\Livewire;

use App\Models\JenisFile;
use App\Models\SourceFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadUserProfile extends Component
{
    use WithFileUploads;

    public $jenis_file_id;

    public $file;

    public $mulai;

    public $selesai;

    public $jenisFiles;

    public $isSip = false;

    public $isSipStr = false;

    public $pelatihan;

    public $jumlah_jam;

    // Modal Preview Dokumen
    public $previewUrl;

    public $previewName;

    public $previewExtension;

    public function mount()
    {
        $this->jenisFiles = JenisFile::all();
    }

    public function updatedJenisFileId()
    {
        $jenis = JenisFile::find($this->jenis_file_id);

        // Hanya SIP yang memerlukan tanggal mulai dan tanggal selesai (STR berlaku seumur hidup)
        $this->isSip = $jenis && str_contains(strtolower($jenis->name), 'sip');
        $this->isSipStr = $this->isSip;

        // Menentukan apakah jenis file adalah Sertifikat Pelatihan
        $this->pelatihan = $jenis && str_contains(strtolower($jenis->name), 'sertifikat pelatihan');

        // Reset field tanggal dan jam jika jenis file tidak membutuhkannya
        if (! $this->isSip && ! $this->pelatihan) {
            $this->mulai = null;
            $this->selesai = null;
            $this->jumlah_jam = null;
        } elseif ($this->isSip) {
            $this->jumlah_jam = null;
        }
    }

    public function setPreviewDokumen($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($file && $file->path && Storage::disk('public')->exists($file->path)) {
            $this->previewUrl = Storage::url($file->path);
            $this->previewName = $file->name ?: basename($file->path);
            $this->previewExtension = strtolower(pathinfo($file->path, PATHINFO_EXTENSION));

            $this->dispatch('open-modal', 'modal-preview-dokumen');
        } else {
            $this->dispatch('swal:alert', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Dokumen tidak ditemukan atau file tidak tersedia di server.',
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'jenis_file_id' => 'required|exists:jenis_files,id',
            'file' => 'required|file|max:2048', // Max 2MB
            'mulai' => $this->isSip ? 'required|date' : 'nullable',
            'selesai' => $this->isSip ? 'required|date|after_or_equal:mulai' : 'nullable',
            'jumlah_jam' => $this->pelatihan ? 'required|integer' : 'nullable|integer', // Sertifikat Pelatihan butuh jumlah jam
        ]);

        // Pastikan jumlah jam diisi manual jika tidak ada tanggal mulai dan selesai
        if ($this->pelatihan && ! $this->mulai && ! $this->selesai) {
            if (! $this->jumlah_jam) {
                $this->dispatch('swal:alert', [
                    'icon' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Jumlah jam harus diisi jika tidak ada tanggal mulai dan selesai.',
                ]);

                return;
            }
        }

        // Ambil data jenis file yang dipilih untuk validasi dan penamaan file
        $jenisFile = JenisFile::find($this->jenis_file_id);
        $jenisFileName = $jenisFile?->name ?? 'Dokumen';

        if ($jenisFile) {
            $namaJenisFile = strtolower(trim($jenisFile->name));

            $isDokumenTerbatas =
                str_contains($namaJenisFile, 'id/ktp') ||
                str_contains($namaJenisFile, 'ktp') ||
                str_contains($namaJenisFile, 'pas foto') ||
                str_contains($namaJenisFile, 'kartu keluarga') ||
                str_contains($namaJenisFile, 'kk');

            if ($isDokumenTerbatas) {
                $alreadyExists = SourceFile::where('user_id', Auth::id())
                    ->where('jenis_file_id', $this->jenis_file_id)
                    ->exists();

                if ($alreadyExists) {
                    $this->dispatch('swal:alert', [
                        'icon' => 'error',
                        'title' => 'Gagal Upload',
                        'text' => 'Dokumen '.$jenisFile->name.' sudah diupload sebelumnya. Tidak dapat mengupload lebih dari satu.',
                    ]);

                    return;
                }
            }
        }

        $path = $this->file->store('dokumen', 'public');

        $userName = Auth::user()->name;

        // Bersihkan karakter '/' atau '\' agar nama file valid
        $cleanJenisFileName = str_replace(['/', '\\'], '-', $jenisFileName);
        $cleanUserName = str_replace(['/', '\\'], '-', $userName);

        $newFileName = $cleanUserName.' - '.$cleanJenisFileName.'.'.$this->file->getClientOriginalExtension();

        SourceFile::create([
            'user_id' => Auth::id(),
            'jenis_file_id' => $this->jenis_file_id,
            'path' => $path,
            'name' => $newFileName,
            'fileable_id' => Auth::id(),
            'fileable_type' => Auth::user()::class,
            'mulai' => ($this->isSip || $this->pelatihan) ? $this->mulai : null,
            'selesai' => ($this->isSip || $this->pelatihan) ? $this->selesai : null,
            'jumlah_jam' => $this->pelatihan ? $this->jumlah_jam : null,
        ]);

        $this->dispatch('swal:alert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'File berhasil diupload.',
        ]);

        $this->reset([
            'file',
            'jenis_file_id',
            'mulai',
            'selesai',
            'isSip',
            'isSipStr',
            'pelatihan',
            'jumlah_jam',
        ]);
    }

    public function download($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($file && $file->path && Storage::disk('public')->exists($file->path)) {
            $safeFileName = str_replace(['/', '\\'], '-', $file->name);

            return Storage::disk('public')->download($file->path, $safeFileName);
        }

        $this->dispatch('swal:alert', [
            'icon' => 'error',
            'title' => 'Gagal',
            'text' => 'Dokumen tidak ditemukan atau file tidak tersedia di server.',
        ]);
    }

    public function deleteFile($id)
    {
        $file = SourceFile::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$file) {
            $this->dispatch('swal:alert', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.',
            ]);

            return;
        }

        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
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

        $this->dispatch('swal:alert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Dokumen berhasil dihapus.',
        ]);
    }

    public function delete($id)
    {
        return $this->deleteFile($id);
    }

    public function render()
    {
        $uploadedFiles = SourceFile::where('user_id', Auth::id())
            ->with('jenisFile')
            ->get();

        return view('livewire.upload-user-profile', [
            'uploadedFiles' => $uploadedFiles,
        ]);
    }
}
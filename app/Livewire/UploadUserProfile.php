<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisFile;
use App\Models\SourceFile;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class UploadUserProfile extends Component
{
    use WithFileUploads;

    public $jenis_file_id;
    public $file;
    public $mulai;
    public $selesai;
    public $jenisFiles;
    public $isSipStr = false;

    public function mount()
    {
        $this->jenisFiles = JenisFile::all();
    }

    public function updatedJenisFileId()
    {
        $jenis = JenisFile::find($this->jenis_file_id);
        $this->isSipStr = $jenis && (str_contains(strtolower($jenis->name), 'sip') || str_contains(strtolower($jenis->name), 'str'));
    }

    public function save()
    {
        $this->validate([
            'jenis_file_id' => 'required|exists:jenis_files,id',
            'file' => 'required|file|max:10240', // Max 10MB
            'mulai' => $this->isSipStr ? 'required|date' : 'nullable',
            'selesai' => $this->isSipStr ? 'required|date|after_or_equal:mulai' : 'nullable',
        ]);

        $path = $this->file->store('dokumen', 'public');

        $userName = Auth::user()->name;
        $jenisFileName = JenisFile::find($this->jenis_file_id)?->name ?? 'Dokumen';

        $newFileName = $userName . ' - ' . $jenisFileName . '.' . $this->file->getClientOriginalExtension();

        SourceFile::create([
            'user_id' => Auth::id(),
            'jenis_file_id' => $this->jenis_file_id,
            'path' => $path,
            'name' => $newFileName,
            'fileable_id' => Auth::id(),
            'fileable_type' => Auth::user()::class,
            'mulai' => $this->mulai,
            'selesai' => $this->selesai,
        ]);

        session()->flash('success', 'File berhasil diupload.');
        $this->reset(['file', 'jenis_file_id', 'mulai', 'selesai', 'isSipStr']);
    }

    public function render()
    {
        $uploadedFiles = SourceFile::where('user_id', Auth::id())->get();

        return view('livewire.upload-user-profile', [
            'uploadedFiles' => $uploadedFiles,
        ]);
    }
}

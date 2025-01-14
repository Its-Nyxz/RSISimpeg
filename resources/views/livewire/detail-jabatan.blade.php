<div>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
<h1 style="font-size: 20px; font-weight: bold; margin: 0;">
            {{ $jabatan->nama }}
        </h1>
        <div style="display: flex; gap: 10px;">
            <a href="/jabatanperizinan" style="background-color: #add8e6; border: none; border-radius: 5px; padding: 8px 16px; cursor: pointer; font-size: 14px; text-decoration: none; color: black;">
                Kembali
            </a>
        <button style="background-color: #fff; border: 2px solid #ddd; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
            <img src="https://cdn-icons-png.flaticon.com/512/484/484611.png" alt="Trash Icon" style="width: 20px; height: 20px;">
        </button>
    </div>
</div>

<x-card title="Perizinan">
    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Aset</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Price</label>
            <label><input type="checkbox" checked> New</label>
            <label><input type="checkbox" checked> Edit</label>
            <label><input type="checkbox" checked> Del</label>
            <label><input type="checkbox" checked> Pdf</label>
            <label><input type="checkbox" checked> Xls</label>
            <label><input type="checkbox" checked> Noaktif</label>
            <label><input type="checkbox" checked> Reaktif</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">History</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> View</label>
            <label><input type="checkbox" checked> Newedit</label>
            <label><input type="checkbox" checked> Del</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Trans</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> View</label>
            <label><input type="checkbox" checked> Newedit</label>
            <label><input type="checkbox" checked> Del</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Data</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> kategori</label>
            <label><input type="checkbox" checked> Merk</label>
            <label><input type="checkbox" checked> Barang</label>
            <label><input type="checkbox" checked> Toko</label>
            <label><input type="checkbox" checked> Penanggung jawab</label>
            <label><input type="checkbox" checked> Kategori stok</label>
            <label><input type="checkbox" checked> Lokasi</label>
            <label><input type="checkbox" checked> Lokasi Gudang</label>
            <label><input type="checkbox" checked> Unit Kerja</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Qr</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Print</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Pengaturan</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Pengaturan</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Inventaris</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Edit lokasi penerimaan</label>
            <label><input type="checkbox" checked> Tambah barang datang</label>
            <label><input type="checkbox" checked> Unggah foto barang datang</label>
            <label><input type="checkbox" checked> Edit jumlah diterima</label>
            <label><input type="checkbox" checked> Upload foto bukti</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Permintaan</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Persetujuan jumlah barang</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Peminjaman</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Persetujuan peminjaman aset</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Persetujuan</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Persetujuan</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Kontrak</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Tambah kontak baru</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Pelayanan</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Xls</label>
        </div>
    </div>

    <div class="section" style="margin-bottom: 20px;">
        <div class="section-title">Stok</div>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <div class="controls" style="text-align: right; margin-top: 10px;">
            <a href="#">Select All</a>
            <a href="#" style="color: red; margin-left: 10px;">Reset All</a>
        </div>
        <div class="permissions" style="display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 10px;">
            <label><input type="checkbox" checked> Show Detail</label>
            <label><input type="checkbox" checked> Xls</label>
        </div>
    </div>
</x-card>
</div>

<x-body>
    <h1 class="text-2xl font-bold text-success-900 "><i class="fa-solid fa-file-invoice"></i> Form Add User</h1>

    <div class="grid grid-cols-2 gap-4 mt-6">

        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <!-- ID User -->
            <div>
                <label for="id-user" class="block text-sm font-medium text-success-900">ID User</label>
                <input type="text" id="id-user" placeholder="Dibuat otomatis" disabled
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 text-gray-600 cursor-not-allowed p-2.5" />
            </div>

            <!-- No Telepon Aktif -->
            <div>
                <label for="phone" class="block text-sm font-medium text-success-900">No Telepon Aktif*</label>
                <input type="text" id="phone" placeholder="Masukkan nomor telepon" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-success-500 focus:border-success-500 p-2.5" />
            </div>

            <!-- Tipe User -->
            <div>
                <label for="user-type" class="block text-sm font-medium text-success-900">Tipe User*</label>
                <select id="user-type" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    <option value="kepegawaian">Kepegawaian</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <!-- Buat Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-success-900">Buat Password*</label>
                <input type="password" id="password" placeholder="Masukkan password" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-success-500 focus:border-success-500 p-2.5" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-success-900">Email*</label>
                <input type="email" id="email" placeholder="Masukkan email" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-success-500 focus:border-success-500 p-2.5" />
            </div>

            <!-- Ulangi Password -->
            <div>
                <label for="confirm-password" class="block text-sm font-medium text-success-900">Ulangi
                    Password*</label>
                <input type="password" id="confirm-password" placeholder="Ulangi password" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-success-500 focus:border-success-500 p-2.5" />
            </div>

            <!-- Nama Lengkap -->
            <div class="col-span-2">
                <label for="full-name" class="block text-sm font-medium text-success-900">Nama Lengkap*</label>
                <input type="text" id="full-name" placeholder="Masukkan nama lengkap" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-success-500 focus:border-success-500 p-2.5" />
            </div>

            <!-- Note -->
            <div class="col-span-2 text-sm text-gray-600">
                NOTE: Inputan bertanda * wajib diisi
            </div>

            <!-- Submit Button -->
            <div class="col-span-2 flex justify-end">
                <button type="submit"
                    class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
                </button>
            </div>
        </div>
    </div>


</x-body>

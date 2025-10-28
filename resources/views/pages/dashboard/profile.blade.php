<x-dashboard-layout :title="$title">
    <div class="page-title mx-0 md:mx-5 mt-5">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-6 mx-0 md:mx-5">

        <h2 class="font-semibold text-lg mb-4 text-gray-700">Informasi Profil</h2>

        <table class="text-sm text-gray-700 w-full">
            <tr>
                <th class="text-left w-1/4 py-2">Nama</th>
                <td>: {{ $user->user_data->nama }}</td>
            </tr>
            <tr>
                <th class="text-left py-2">Email</th>
                <td>: {{ $user->email }}</td>
            </tr>
            <tr>
                <th class="text-left py-2">Telepon</th>
                <td>: {{ $user->user_data->telepon ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-left py-2">Alamat</th>
                <td>: {{ $user->user_data->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-left py-2">Status</th>
                <td>: {{ $user->status ?? 'aktif' }}</td>
            </tr>
        </table>

        <div class="flex gap-3 justify-end mt-5">
            <button data-modal-target="editProfile" data-modal-toggle="editProfile"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md">Edit Profil</button>

            <button data-modal-target="gantiPassword" data-modal-toggle="gantiPassword"
                class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-md">Ganti
                Password</button>
        </div>

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @endpush


    {{-- Modal Edit Profil --}}
    <x-modal id="editProfile" title="Edit Profil">
        <form method="POST" action="{{ route('dashboard.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" value="{{ $user->user_data->nama }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" name="telepon" value="{{ $user->user_data->telepon }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">{{ $user->user_data->alamat }}</textarea>
            </div>

            <div class="flex justify-end mt-4">
                <button data-modal-hide="editProfile" type="button"
                    class="py-2.5 px-5 text-sm bg-gray-100 border rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit"
                    class="ml-3 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Ganti Password --}}
    <x-modal id="gantiPassword" title="Ganti Password">
        <form method="POST" action="{{ route('dashboard.profile.password') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Password Lama</label>
                <input type="password" name="current_password"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="new_password"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
            </div>

            <div class="flex justify-end mt-4">
                <button data-modal-hide="gantiPassword" type="button"
                    class="py-2.5 px-5 text-sm bg-gray-100 border rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit"
                    class="ml-3 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
            </div>
        </form>
    </x-modal>

    <x-alert />
</x-dashboard-layout>

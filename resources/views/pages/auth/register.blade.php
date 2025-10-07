<x-auth-layout :title="$title">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-[80%] xl:max-w-[50%] xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Daftar Akun
                </h1>
                <form class="space-y-4 md:space-y-6" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                            <input type="text" name="nama" id="nama"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 {{ $errors->has('nama') ? 'error' : '' }}"
                                placeholder="John Doe" required>
                            @error('nama')
                                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 {{ $errors->has('email') ? 'error' : '' }}"
                                placeholder="JohnDoe@gmail.com" required>
                            @error('email')
                                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 {{ $errors->has('password') ? 'error' : '' }}"
                                required>
                            @error('password')
                                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="no_telepon" class="block mb-2 text-sm font-medium text-gray-900">Nomor
                                Telepon</label>
                            <input type="number" name="no_telepon" id="no_telepon" placeholder="08123456789"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 {{ $errors->has('no_telepon') ? 'error' : '' }}"
                                required>
                            @error('no_telepon')
                                <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat
                            Lengkap</label>
                        <textarea name="alamat" id="alamat" cols="30"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 {{ $errors->has('alamat') ? 'error' : '' }}"
                            required></textarea>
                        @error('alamat')
                            <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer">
                        Daftar
                    </button>

                    <p class="text-sm font-light text-gray-500 text-center">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="font-medium text-blue-600 hover:underline cursor-pointer">
                            Login
                        </a>
                    </p>
                </form>

            </div>
        </div>
    </div>
    @if ($errors->any())
        @push('scripts')
            <script>
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan, silahkan coba lagi.",
                    icon: "error"
                });
            </script>
        @endpush
    @endif
</x-auth-layout>

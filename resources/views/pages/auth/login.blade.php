<x-auth-layout :title="$title">
    @if (session()->has('success'))
        @push('scripts')
            <script>
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success"
                });
            </script>
        @endpush
    @endif
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
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Login Akun
                </h1>
                <form class="space-y-4 md:space-y-6" action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-l block w-full p-2.5 {{ $errors->has('email') ? 'error' : '' }}"
                            placeholder="name@company.com" required>
                        @error('email')
                            <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-l block w-full p-2.5 {{ $errors->has('password') ? 'error' : '' }}"
                            required>
                        @error('password')
                            <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" name="remember" aria-describedby="remember" type="checkbox"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500">Remember me</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer">Login</button>
                    <p class="text-center text-sm font-light text-gray-500">
                        Belum punya akun? <a href="{{ route('register') }}"
                            class="font-medium text-blue-600 hover:underline">Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-auth-layout>

@if ($errors->any())
    @push('scripts')
        <script>
            const errors = `{!! '<ul>' . collect($errors->all())->map(fn($e) => "<li>{$e}</li>")->implode('') . '</ul>' !!}`;

            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan!",
                html: errors,
                confirmButtonText: "OK"
            });
        </script>
    @endpush
@endif

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

<script>
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();

        const form = this;

        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

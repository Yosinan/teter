@if(session()->has('success'))
    {{-- <div class="alert alert-success alert-dismissible fade show" role="alert"> --}}
        <div class="fixed top-0 transform bg-laravel text-black px-48 py-3 left-1/2 -translate-x-1/2">
        <p>{{ session('success') }}</p>
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    </div>
    
@endsession
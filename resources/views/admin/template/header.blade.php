<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand fw-semibold">BS CMS</span>
        <div class="d-flex align-items-center text-white">
            @if(auth()->check())
                <span class="me-3 small">{{ auth()->user()->name }}</span>
                @if(Route::has('logout'))
                    <form action="{{ route('logout') }}" method="POST" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Çıkış</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</nav>

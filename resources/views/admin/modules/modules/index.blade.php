@extends('admin.template.index')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 mb-1">Modüller</h1>
            <p class="text-muted mb-0">Sitede kullanılan hazır modüllerin durumunu görüntüleyin.</p>
        </div>
    </div>

    <div class="row g-3">
        @forelse($modules as $module)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $module->name }}</h5>
                        <p class="text-muted small">Tip: {{ $module->type }}</p>
                        <span class="badge {{ $module->is_active ? 'bg-success' : 'bg-secondary' }} w-auto mb-3">{{ $module->is_active ? 'Aktif' : 'Pasif' }}</span>
                        <a href="{{ route('admin.modules.' . $module->type) }}" class="btn btn-outline-primary mt-auto">Düzenle</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Henüz modül bulunmuyor.</div>
            </div>
        @endforelse
    </div>
@endsection

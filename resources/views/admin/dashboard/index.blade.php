@extends('admin.template.index')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Kontrol Paneli</h1>
            <p class="text-muted mb-0">Sistemdeki içerikleri hızlıca yönetin.</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted text-uppercase small">Sayfa</span>
                    <h2 class="display-6">{{ $pageCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted text-uppercase small">Menü</span>
                    <h2 class="display-6">{{ $menuCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted text-uppercase small">Modül</span>
                    <h2 class="display-6">{{ $moduleCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted text-uppercase small">Ayar Kaydı</span>
                    <h2 class="display-6">{{ $settingCount }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection

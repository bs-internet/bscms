@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Genel Ayarlar</h1>
    <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Site adı</label>
                        <input type="text" class="form-control" name="settings[site_name]" value="{{ $settings['site_name'] ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kısa açıklama</label>
                        <input type="text" class="form-control" name="settings[tagline]" value="{{ $settings['tagline'] ?? '' }}">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Varsayılan dil</label>
                            <input type="text" class="form-control" name="settings[default_language]" value="{{ $settings['default_language'] ?? 'tr' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ana renk</label>
                            <input type="text" class="form-control" name="settings[primary_color]" value="{{ $settings['primary_color'] ?? '#0d6efd' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small">Bu ayarlar sitenin genel başlık ve temalarını belirler.</p>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
@endsection

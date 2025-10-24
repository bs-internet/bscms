@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Bülten Modülü</h1>
    <form action="{{ route('admin.modules.newsletter.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" class="form-control" name="content[title]" value="{{ $module->content['title'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kısa açıklama</label>
                        <textarea name="content[description]" rows="4" class="form-control">{{ $module->content['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Arka plan rengi</label>
                        <input type="text" class="form-control" name="content[background]" value="{{ $module->content['background'] ?? '#f5f5f5' }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="newsletter_is_active" name="is_active" value="1" {{ $module->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="newsletter_is_active">Modül aktif</label>
                    </div>
                    <p class="text-muted small">Bülten modülü e-posta aboneliği toplayan alanı temsil eder.</p>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Şirket Bilgileri</h1>
    <form action="{{ route('admin.settings.company.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Şirket adı</label>
                        <input type="text" class="form-control" name="settings[company_name]" value="{{ $settings['company_name'] ?? '' }}">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" class="form-control" name="settings[phone]" value="{{ $settings['phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-posta</label>
                            <input type="email" class="form-control" name="settings[email]" value="{{ $settings['email'] ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Adres</label>
                        <textarea name="settings[address]" rows="3" class="form-control">{{ $settings['address'] ?? '' }}</textarea>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">LinkedIn</label>
                            <input type="url" class="form-control" name="settings[linkedin]" value="{{ $settings['linkedin'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram</label>
                            <input type="url" class="form-control" name="settings[instagram]" value="{{ $settings['instagram'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small">Şirket iletişim bilgileriniz ziyaretçileriniz tarafından görüntülenir.</p>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
@endsection

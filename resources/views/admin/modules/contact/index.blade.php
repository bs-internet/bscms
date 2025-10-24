@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">İletişim Modülü</h1>
    <form action="{{ route('admin.modules.contact.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <textarea name="content[address]" rows="3" class="form-control">{{ $module->content['address'] ?? '' }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="content[phone]" class="form-control" value="{{ $module->content['phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="content[email]" class="form-control" value="{{ $module->content['email'] ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Harita bağlantısı</label>
                        <input type="url" name="content[map_url]" class="form-control" value="{{ $module->content['map_url'] ?? '' }}" placeholder="https://maps.google.com/...">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="contact_is_active" name="is_active" value="1" {{ $module->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="contact_is_active">Modül aktif</label>
                    </div>
                    <p class="text-muted small">İletişim modülü şirketin adres ve iletişim kanallarını içerir.</p>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Slider Modülü</h1>
    <form action="{{ route('admin.modules.slider.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" class="form-control" name="content[headline]" value="{{ $module->content['headline'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metin</label>
                        <textarea name="content[text]" rows="4" class="form-control">{{ $module->content['text'] ?? '' }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Buton metni</label>
                            <input type="text" class="form-control" name="content[button_text]" value="{{ $module->content['button_text'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buton bağlantısı</label>
                            <input type="text" class="form-control" name="content[button_url]" value="{{ $module->content['button_url'] ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Arka plan görseli</label>
                        <input type="text" class="form-control" name="content[background]" value="{{ $module->content['background'] ?? '' }}" placeholder="/storage/slider.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="slider_is_active" name="is_active" value="1" {{ $module->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="slider_is_active">Modül aktif</label>
                    </div>
                    <p class="text-muted small">Slider modülü ana sayfada öne çıkan mesajınızı göstermenizi sağlar.</p>
                    <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
@endsection

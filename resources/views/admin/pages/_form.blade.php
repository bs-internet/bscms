@csrf
<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $page->title ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Özet</label>
                    <input type="text" name="summary" class="form-control" value="{{ old('summary', $page->summary ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">İçerik</label>
                    <textarea name="body" rows="8" class="form-control" required>{{ old('body', $page->body ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Bağlantı</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug ?? '') }}" placeholder="ornek-sayfa">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Yayınla</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yayın Tarihi</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ old('published_at', optional($page->published_at ?? null)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold">SEO</h6>
                <div class="mb-3">
                    <label class="form-label">Meta Başlık</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Açıklama</label>
                    <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Vazgeç</a>
    <button type="submit" class="btn btn-primary">Kaydet</button>
</div>

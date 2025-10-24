@extends('admin.template.index')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 mb-1">Sayfalar</h1>
            <p class="text-muted mb-0">Kurumsal sayfalarınızı düzenleyin ve yayınlayın.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Yeni sayfa</a>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Başlık veya bağlantı ara">
            <button class="btn btn-outline-secondary" type="submit">Ara</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Başlık</th>
                    <th>Bağlantı</th>
                    <th>Yayın</th>
                    <th class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td class="text-muted">/{{ $page->slug }}</td>
                        <td>
                            @if($page->is_published)
                                <span class="badge bg-success">Yayında</span>
                            @else
                                <span class="badge bg-secondary">Taslak</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu sayfayı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Henüz sayfa oluşturulmamış.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $pages->withQueryString()->links() }}
        </div>
    </div>
@endsection

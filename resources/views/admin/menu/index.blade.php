@extends('admin.template.index')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 mb-1">Menüler</h1>
            <p class="text-muted mb-0">Sitenizin navigasyon yapısını yönetin.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Yeni menü oluştur</h5>
                    <form action="{{ route('admin.menu.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ad</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konum</label>
                            <input type="text" name="location" class="form-control" placeholder="header" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <input type="text" name="description" class="form-control" placeholder="Üst menü">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Menüyü kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            @forelse($menus as $menu)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="card-title mb-0">{{ $menu->name }}</h5>
                                <small class="text-muted">Konum: {{ $menu->location }}</small>
                            </div>
                            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Menüyü silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                            </form>
                        </div>
                        <form action="{{ route('admin.menu.update', $menu) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $menu->name }}">
                            <input type="hidden" name="location" value="{{ $menu->location }}">
                            <input type="hidden" name="description" value="{{ $menu->description }}">

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Adres</th>
                                        <th>Hedef</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($items = $menu->items->values())
                                    @for($i = 0; $i < max($items->count() + 2, 3); $i++)
                                        @php($item = $items->get($i) ?? null)
                                        <tr>
                                            <td><input type="text" name="items[{{ $i }}][title]" class="form-control" value="{{ $item->title ?? '' }}"></td>
                                            <td><input type="text" name="items[{{ $i }}][url]" class="form-control" value="{{ $item->url ?? '' }}" placeholder="/ornek"></td>
                                            <td>
                                                @php($target = $item->target ?? '_self')
                                                <select name="items[{{ $i }}][target]" class="form-select">
                                                    <option value="_self" {{ $target === '_self' ? 'selected' : '' }}>Aynı sekme</option>
                                                    <option value="_blank" {{ $target === '_blank' ? 'selected' : '' }}>Yeni sekme</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary">Menüyü güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Henüz menü oluşturulmadı.</div>
            @endforelse
        </div>
    </div>
@endsection

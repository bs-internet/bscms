@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Profilim</h1>
    <form action="{{ route('admin.settings.profile.update') }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ad soyad</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yeni şifre</label>
                        <input type="password" class="form-control" name="password" autocomplete="new-password">
                        <small class="text-muted">Şifre güncellemek istemiyorsanız boş bırakın.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yeni şifre (tekrar)</label>
                        <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small">Bilgileriniz paneldeki kullanıcı adı ve giriş bilgilerini belirler.</p>
                    <button type="submit" class="btn btn-primary w-100">Profili güncelle</button>
                </div>
            </div>
        </div>
    </form>
@endsection

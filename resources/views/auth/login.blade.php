<form action="/login" method="post">
    @csrf
    <div class="mb-3">
        <label class="form-label">E-posta Adresi</label>
        <input type="text" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Şifre</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-block btn-warning">Oturum Aç</button>
    </div>
</form>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif

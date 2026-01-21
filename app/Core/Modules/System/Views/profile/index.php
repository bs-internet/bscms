<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Profilim</h1>
        <small>Hesap bilgilerinizi güncelleyin.</small>
    </div>
</div>

<article>
    <form action="/App\Core\Modules\System\Views\profile\index/update" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div>
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" required
                    value="<?= old('username', $user->username) ?>">

                <label for="email">E-posta Adresi</label>
                <input type="email" id="email" name="email" required value="<?= old('email', $user->email) ?>">
            </div>

            <div>
                <fieldset>
                    <legend>Şifre Değiştir</legend>
                    <small>Şifrenizi değiştirmek istemiyorsanız boş bırakın.</small>

                    <label for="password">Yeni Şifre</label>
                    <input type="password" id="password" name="password" autocomplete="new-password">

                    <label for="password_confirm">Yeni Şifre (Tekrar)</label>
                    <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
                </fieldset>
            </div>
        </div>

        <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
            <i class="fa-solid fa-save"></i> Güncelle
        </button>
    </form>
</article>

<?= $this->endSection() ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Şifreyi Sıfırla | BSCMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/admin/css/auth.css">
</head>

<body class="centered-page">
    <div class="wrapper">
        <div style="margin-bottom: 1.5rem; font-size: 2rem; color: var(--primary);">
            <i class="fa-solid fa-key"></i>
        </div>
        <h1>Yeni Şifre Belirle</h1>
        <p>Lütfen yeni şifrenizi giriniz.</p>

        <!-- Error Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-box">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>
                    <?= session()->getFlashdata('error') ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-box">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <span>
                            <?= esc($error) ?>
                        </span>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif; ?>

        <form action="/admin/reset-password/update" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <div class="input-group password-group">
                <div style="position: relative;">
                    <input type="password" name="password" placeholder="Yeni Şifre" required class="mb-2">
                    <button type="button" class="toggle-password" aria-label="Şifreyi Göster">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="input-group password-group">
                <div style="position: relative;">
                    <input type="password" name="password_confirm" placeholder="Yeni Şifre (Tekrar)" required>
                    <button type="button" class="toggle-password" aria-label="Şifreyi Göster">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit">Şifreyi Güncelle</button>
        </form>

        <a href="/App\Core\Modules\Auth\Views\login" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Giriş'e Dön
        </a>
    </div>

    <!-- Auth JS -->
    <script src="/assets/admin/js/auth.js"></script>
</body>

</html>

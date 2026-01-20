<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş | BSCMS Admin</title>

    <!-- Fonts: Inter for that clean SaaS look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Pico.css (Version 2) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/admin/css/auth.css">
</head>

<body class="login-page">

    <!-- Left Panel: Brand -->
    <div class="split-left">
        <div class="brand-content">
            <div class="brand-logo">
                <i class="fa-solid fa-shapes"></i> BSCMS
            </div>
        </div>

        <div class="brand-quote">
            <blockquote>
                "İçerik yönetiminde basitlik, en yüksek mühendisliktir."
            </blockquote>
            <footer>— BSCMS Yönetim Paneli</footer>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="split-right">
        <div class="login-wrapper">
            <h1>Hoşgeldiniz</h1>
            <p class="subtitle">Yönetim paneline erişmek için giriş yapın.</p>

            <!-- Error Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert-box">
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <span><?= esc($error) ?></span>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="/admin/login" method="post">
                <?= csrf_field() ?>

                <div class="input-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" placeholder="Örn. admin" required
                        value="<?= old('username') ?>" autocomplete="username">
                </div>

                <div class="input-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                        autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">
                    Giriş Yap
                </button>
            </form>

            <div class="back-link">
                <a href="/">
                    <i class="fa-solid fa-arrow-left"></i> Siteye Geri Dön
                </a>
            </div>
        </div>
    </div>

</body>

</html>
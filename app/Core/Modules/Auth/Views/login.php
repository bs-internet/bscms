<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= lang('Auth.login_title') ?></title>

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
                "<?= lang('Auth.quote') ?? 'İçerik yönetiminde basitlik, en yüksek mühendisliktir.' ?>"
            </blockquote>
            <footer>— <?= lang('Admin.copyright') ?></footer>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="split-right">
        <div class="login-wrapper">
            <h1><?= lang('Auth.welcome_back') ?></h1>
            <p class="subtitle"><?= lang('Auth.login_subtitle') ?></p>

            <!-- Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-box" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <i class="fa-solid fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

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
                    <label for="username"><?= lang('Auth.username') ?></label>
                    <input type="text" id="username" name="username" placeholder="Örn. admin" required
                        value="<?= old('username') ?>" autocomplete="username" aria-required="true"
                        aria-describedby="username-help">
                </div>

                <div class="input-group password-group">
                    <div class="input-header">
                        <label for="password"><?= lang('Auth.password') ?></label>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            autocomplete="current-password" aria-required="true" aria-describedby="password-help">
                        <button type="button" class="toggle-password" aria-label="Şifreyi Göster/Gizle" tabindex="-1">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember"
                            aria-label="<?= lang('Auth.remember_me') ?>">
                        <span><?= lang('Auth.remember_me') ?></span>
                    </label>
                    <a href="/admin/forgot-password" class="forgot-password"><?= lang('Auth.forgot_password') ?></a>
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <span class="btn-text"><?= lang('Auth.login_button') ?></span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fa-solid fa-spinner fa-spin"></i> <?= lang('Auth.logging_in') ?>
                    </span>
                </button>
            </form>

            <div class="back-link">
                <a href="/">
                    <i class="fa-solid fa-arrow-left"></i> <?= lang('Auth.back_to_site') ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Auth JS -->
    <script src="/assets/admin/js/auth.js"></script>
    </div>
    </div>

</body>

</html>
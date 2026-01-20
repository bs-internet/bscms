<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş Yap | BSCMS</title>
    <!-- Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--pico-background-color);
        }

        main {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: bold;
            color: var(--pico-primary);
        }
    </style>
</head>

<body>
    <main class="container">
        <div class="logo">
            BSCMS
        </div>
        <article>
            <form action="/admin/login" method="post">
                <?= csrf_field() ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div
                        style="background-color: var(--pico-del-color); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div
                        style="background-color: var(--pico-del-color); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div>
                                <?= esc($error) ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>

                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" placeholder="admin" required
                    value="<?= old('username') ?>">

                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="contrast">Giriş Yap</button>
            </form>
        </article>
        <div style="text-align: center; margin-top: 1rem;">
            <small><a href="/" class="secondary">Siteye Dön</a></small>
        </div>
    </main>
</body>

</html>
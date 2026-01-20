<!DOCTYPE html>
<html lang="tr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BSCMS Admin</title>

    <!-- Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/admin.css') ?>">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>

    <aside id="sidebar">
        <a href="/admin" class="sidebar-brand">
            <i class="fa-solid fa-layer-group"></i> BSCMS
        </a>

        <?= $this->include('admin/layout/partials/sidebar') ?>
    </aside>

    <main id="main-content">
        <header class="container-fluid">
            <nav>
                <ul>
                    <li><strong>
                            <?= $title ?? 'Panel' ?>
                        </strong></li>
                </ul>
                <ul>
                    <li><a href="/" target="_blank" class="secondary">Siteyi Görüntüle</a></li>
                    <li>
                        <details class="dropdown">
                            <summary>Admin</summary>
                            <ul dir="rtl">
                                <li><a href="/admin/logout">Çıkış Yap</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </nav>
        </header>

        <hr>

        <!-- Flash Messages -->
        <?= $this->include('admin/layout/partials/alerts') ?>

        <div class="container-fluid">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <?= $this->include('admin/layout/partials/scripts') ?>
</body>

</html>
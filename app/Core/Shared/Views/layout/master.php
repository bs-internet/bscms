<!DOCTYPE html>
<html lang="tr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta name="csrf-value" content="<?= csrf_hash() ?>">
    <title><?= lang('Admin.dashboard') ?> | BSCMS Admin</title>

    <!-- Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/admin.css') ?>?v=<?= time() ?>">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>

    <aside id="sidebar">
        <div class="sidebar-header">
            <a href="/admin" class="sidebar-brand">
                <i class="fa-solid fa-shapes"></i> BSCMS
            </a>
        </div>

        <?= $this->include('App\Core\Shared\Views\layout/partials/sidebar') ?>
    </aside>

    <main id="main-content">
        <header class="main-header">
            <div class="header-title">
                <h1><?= $title ?? lang('Admin.dashboard') ?></h1>
            </div>
            <div class="header-actions">
                <form action="/admin/settings/clear-cache" method="post" style="display:inline; margin:0;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-secondary-outline" title="Önbellek Temizle" style="border:none; cursor:pointer;">
                        <i class="fa-solid fa-broom"></i>
                    </button>
                </form>
                <a href="/" target="_blank" class="btn-secondary-outline">
                    <i class="fa-solid fa-external-link-alt"></i> <?= lang('Admin.view_site') ?>
                </a>
                <details class="dropdown" style="position:relative;">
                    <summary style="cursor:pointer; font-weight:500;">
                        <i class="fa-solid fa-user-circle"></i> Admin
                    </summary>
                    <ul dir="rtl"
                        style="position:absolute; right:0; top:100%; background:#fff; border:1px solid #e2e8f0; border-radius:0.5rem; padding:0.5rem; min-width:150px; box-shadow:var(--shadow-md); z-index:50;">
                        <li><a href="/admin/logout"
                                style="display:block; padding:0.5rem; color:var(--text-main);"><?= lang('Admin.logout') ?></a>
                        </li>
                    </ul>
                </details>
            </div>
        </header>

        <div class="main-body">
            <!-- Flash Messages -->
            <?= $this->include('App\Core\Shared\Views\layout/partials/alerts') ?>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <?= $this->include('App\Core\Shared\Views\layout/partials/scripts') ?>
</body>

</html>
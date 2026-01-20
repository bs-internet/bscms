<!DOCTYPE html>
<html lang="tr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BSCMS Admin</title>

    <!-- Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/custom.css') ?>">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --pico-font-size: 100%;
        }

        body {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: 250px;
            padding: 2rem;
            border-right: 1px solid var(--pico-muted-border-color);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }

        #main-content {
            flex-grow: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .sidebar-brand {
            margin-bottom: 2rem;
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--pico-primary);
            text-decoration: none;
        }

        .sidebar-nav li {
            list-style: none;
            margin-bottom: 0.5rem;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: var(--pico-color);
            display: block;
            padding: 0.5rem;
            border-radius: var(--pico-border-radius);
            transition: background-color 0.2s;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: var(--pico-card-background-color);
            color: var(--pico-primary);
        }

        .sidebar-nav i {
            width: 24px;
            text-align: center;
            margin-right: 8px;
        }

        /* Utility for htmx loading */
        .htmx-indicator {
            opacity: 0;
            transition: opacity 200ms ease-in;
        }

        .htmx-request .htmx-indicator {
            opacity: 1;
        }

        .htmx-request.htmx-indicator {
            opacity: 1;
        }
    </style>
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
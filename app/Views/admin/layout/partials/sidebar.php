<nav class="sidebar-nav">
    <ul>
        <li>
            <a href="/admin/dashboard" class="<?= url_is('admin/dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
        </li>

        <li><small>İÇERİK YÖNETİMİ</small></li>

        <?php
        // We need to fetch content types here. 
        // Ideally this should be passed from a View composer or similar, 
        // but for simplicity in CI4 legacy style we might use the service locator or hardcode for now if Types are static.
        // Better: ContentTypes share a common menu logic.
        // Let's assume we maintain standard modules.
        $contentTypeRepo = service('contentTypeRepository');
        $contentTypes = $contentTypeRepo->getAll();
        ?>

        <?php foreach ($contentTypes as $ct): ?>
            <li>
                <a href="/admin/content-type/<?= $ct->id ?>/contents" class="<?= url_is("admin/content-type/{$ct->id}*") ? 'active' : '' ?>">
                    <i class="fa-regular fa-file-lines"></i>
                    <?= esc($ct->title) ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li><small>MODÜLLER</small></li>

        <li>
            <a href="/admin/categories" class="<?= url_is('admin/categories*') ? 'active' : '' ?>">
                <i class="fa-solid fa-tags"></i> Kategoriler
            </a>
        </li>
        <li>
            <a href="/admin/forms" class="<?= url_is('admin/forms*') ? 'active' : '' ?>">
                <i class="fa-solid fa-inbox"></i> Formlar
            </a>
        </li>
        <li>
            <a href="/admin/menus" class="<?= url_is('admin/menus*') ? 'active' : '' ?>">
                <i class="fa-solid fa-bars"></i> Menüler
            </a>
        </li>
        <li>
            <a href="/admin/media" class="<?= url_is('admin/media*') ? 'active' : '' ?>">
                <i class="fa-solid fa-images"></i> Medya
            </a>
        </li>

        <li><small>SİSTEM</small></li>

        <li>
            <a href="/admin/settings" class="<?= url_is('admin/settings*') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> Ayarlar
            </a>
        </li>
        <li>
            <a href="/admin/content-types" class="<?= url_is('admin/content-types*') ? 'active' : '' ?>">
                <i class="fa-solid fa-code"></i> İçerik Türleri
            </a>
        </li>
    </ul>
</nav>
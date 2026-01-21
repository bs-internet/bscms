<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Menü Düzenle:
            <?= esc($menu->name) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/menus" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="grid">
    <!-- Menu Settings -->
    <article>
        <header><strong>Ayarlar</strong></header>
        <form action="/admin/menus/update/<?= $menu->id ?>" method="post">
            <?= csrf_field() ?>

            <label for="name">Menü İsmi</label>
            <input type="text" id="name" name="name" required value="<?= old('name', $menu->name) ?>">

            <label for="location">Konum</label>
            <input type="text" id="location" name="location" required value="<?= old('location', $menu->location) ?>">

            <button type="submit" class="primary" style="width: 100%;">Güncelle</button>
        </form>
    </article>

    <!-- Menu Items (Placeholder for Sortable Nested List) -->
    <article style="grid-column: span 2;">
        <header><strong>Menü Öğeleri (Linkler)</strong></header>

        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-list-check fa-2x"></i><br>
            Menü Öğesi Yönetimi (Sürükle & Bırak) Yakında...
        </div>

        <!-- Future implementation: List existing items, Add Item form -->
        <?php if (!empty($items)): ?>
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <?= esc($item->title) ?> (
                        <?= esc($item->url) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </article>
</div>

<?= $this->endSection() ?>

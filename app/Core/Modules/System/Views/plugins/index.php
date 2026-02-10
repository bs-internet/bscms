<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="header-actions">
    <h1><i class="fa-solid fa-puzzle-piece"></i> Eklentiler</h1>
    <a href="#" class="btn btn-secondary"><i class="fa-solid fa-upload"></i> Eklenti Yükle</a>
</div>

<article class="card">
    <table class="striped">
        <thead>
            <tr>
                <th>Eklenti Adı</th>
                <th>Klasör Yolu</th>
                <th>Durum</th>
                <th class="text-right">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($plugins)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">Hiç eklenti bulunamadı.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($plugins as $plugin): ?>
                    <tr>
                        <td>
                            <strong><?= esc($plugin['name']) ?></strong>
                        </td>
                        <td><small class="text-muted"><?= esc($plugin['path']) ?></small></td>
                        <td>
                            <?php if ($plugin['active']): ?>
                                <span class="badge" style="background-color: var(--pico-ins-color); color: white;">Aktif</span>
                            <?php else: ?>
                                <span class="badge" style="background-color: var(--pico-muted-color); color: white;">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <form action="/admin/plugins/toggle/<?= esc($plugin['name']) ?>" method="post"
                                style="display:inline;">
                                <?= csrf_field() ?>
                                <?php if ($plugin['active']): ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Pasifleştir</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Aktifleştir</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</article>

<?= $this->endSection() ?>
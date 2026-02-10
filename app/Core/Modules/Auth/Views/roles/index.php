<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Rol Yönetimi</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/roles/create" role="button">
            <i class="fa-solid fa-plus"></i> Yeni Rol
        </a>
    </div>
</div>

<article>
    <table role="grid">
        <thead>
            <tr>
                <th>ID</th>
                <th>Rol Adı</th>
                <th>Slug</th>
                <th>Açıklama</th>
                <th>Oluşturulma</th>
                <th style="text-align: right;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td>
                        <?= $role->id ?>
                    </td>
                    <td>
                        <strong>
                            <?= esc($role->name) ?>
                        </strong>
                    </td>
                    <td><code><?= esc($role->slug) ?></code></td>
                    <td>
                        <?= esc($role->description) ?>
                    </td>
                    <td>
                        <?= date('d.m.Y', strtotime($role->created_at)) ?>
                    </td>
                    <td style="text-align: right;">
                        <div class="grid" style="justify-content: end; grid-template-columns: auto auto; gap: 0.5rem;">
                            <a href="/admin/roles/edit/<?= $role->id ?>" class="secondary outline btn-sm" role="button">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <?php if ($role->slug !== 'super-admin'): ?>
                                <a href="/admin/roles/delete/<?= $role->id ?>"
                                    onclick="return confirm('Bu rolü silmek istediğinize emin misiniz?')"
                                    class="contrast outline btn-sm" role="button">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</article>

<?= $this->endSection() ?>
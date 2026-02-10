<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>
            <?= esc($title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/roles" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri
        </a>
    </div>
</div>

<article>
    <form action="<?= $role ? '/admin/roles/update/' . $role->id : '/admin/roles/store' ?>" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <!-- Basic Info -->
                <fieldset>
                    <legend>Temel Bilgiler</legend>
                    <label for="name">Rol Adı</label>
                    <input type="text" id="name" name="name" required value="<?= old('name', $role->name ?? '') ?>"
                        placeholder="Örn: Editör">

                    <label for="slug">Slug (Kod)</label>
                    <input type="text" id="slug" name="slug" value="<?= old('slug', $role->slug ?? '') ?>"
                        placeholder="Otomatik oluşturulur (opsiyonel)">

                    <label for="description">Açıklama</label>
                    <textarea id="description" name="description"
                        rows="2"><?= old('description', $role->description ?? '') ?></textarea>
                </fieldset>
            </div>

            <!-- Permissions -->
            <?php if (isset($allPermissions)): ?>
                <div>
                    <fieldset>
                        <legend>İzinler</legend>
                        <div style="max-height: 600px; overflow-y: auto;">
                            <?php foreach ($allPermissions as $module => $permissions): ?>
                                <details open>
                                    <summary><strong>
                                            <?= ucfirst($module) ?>
                                        </strong></summary>
                                    <?php foreach ($permissions as $perm): ?>
                                        <label>
                                            <input type="checkbox" name="permissions[]" value="<?= $perm->id ?>"
                                                <?= in_array($perm->id, $rolePermissionIds ?? []) ? 'checked' : '' ?>
                                            >
                                            <?= esc($perm->name) ?> <small class="secondary">(
                                                <?= $perm->slug ?>)
                                            </small>
                                        </label>
                                    <?php endforeach; ?>
                                </details>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
            <i class="fa-solid fa-save"></i> Kaydet
        </button>
    </form>
</article>

<?= $this->endSection() ?>
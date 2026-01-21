<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Menu.title') ?></h1>
        <small><?= lang('Menu.menu_management') ?></small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/menus/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> <?= lang('Menu.new_menu') ?>
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?= lang('Menu.id') ?></th>
                    <th scope="col"><?= lang('Menu.menu_title') ?></th>
                    <th scope="col"><?= lang('Menu.menu_key') ?></th>
                    <th scope="col" style="text-align: right;"><?= lang('Menu.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menus)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;"><?= lang('Menu.no_records') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td>
                                <?= $menu->id ?>
                            </td>
                            <td>
                                <?= esc($menu->name) ?>
                            </td>
                            <td><code><?= esc($menu->location) ?></code></td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/admin/menus/edit/<?= $menu->id ?>" role="button" class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/admin/menus/delete/<?= $menu->id ?>"
                                        hx-confirm="<?= lang('Menu.delete_confirm') ?>" role="button"
                                        class="contrast outline btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </figure>
</article>

<?= $this->endSection() ?>
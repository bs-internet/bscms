<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Component.title') ?></h1>
        <small><?= lang('Component.component_management') ?></small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/components/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> <?= lang('Component.new_component') ?>
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?= lang('Component.id') ?></th>
                    <th scope="col"><?= lang('Component.component_title') ?></th>
                    <th scope="col"><?= lang('Component.component_key') ?></th>
                    <th scope="col">Global?</th>
                    <th scope="col" style="text-align: right;"><?= lang('Component.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- Mock Data or Empty State as Controller not fully read but structure implied -->
                <tr>
                    <td colspan="5" style="text-align: center;"><?= lang('Component.no_records') ?></td>
                </tr>
            </tbody>
        </table>
    </figure>
    <!-- Note: Assuming ComponentController logic passes 'components' variable similar to other controllers -->
</article>

<?= $this->endSection() ?>
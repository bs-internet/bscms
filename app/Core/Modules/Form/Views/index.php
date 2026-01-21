<?= $this->extend('App\Core\Shared\Views\layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Formlar</h1>
        <small>Web sitenizdeki formları yönetin</small>
    </div>
    <div style="text-align: right;">
        <a href="/App\Core\Modules\Form\Views/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> Yeni Form
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Başlık</th>
                    <th scope="col">Kod (Key)</th>
                    <th scope="col" style="text-align: center;">Mesajlar</th>
                    <th scope="col" style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($forms)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Henüz form oluşturulmamış.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($forms as $form): ?>
                        <tr>
                            <td>
                                <?= $form->id ?>
                            </td>
                            <td>
                                <?= esc($form->title) ?>
                            </td>
                            <td><code><?= esc($form->form_key) ?></code></td>
                            <td style="text-align: center;">
                                <a href="/App\Core\Modules\Form\Views/<?= $form->id ?>/submissions" role="button" class="outline btn-sm">
                                    Görüntüle
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/App\Core\Modules\Form\Views/edit/<?= $form->id ?>" role="button" class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/App\Core\Modules\Form\Views/delete/<?= $form->id ?>"
                                        hx-confirm="Bu formu ve gelen kutusunu silmek istediğinize emin misiniz?" role="button"
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
<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Form Düzenle:
            <?= esc($form->title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/forms" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="grid">
    <!-- Form Settings -->
    <article>
        <header><strong>Ayarlar</strong></header>
        <form action="/App\Core\Modules\Form\Views\forms\update/<?= $form->id ?>" method="post">
            <?= csrf_field() ?>

            <label for="title">Form Başlığı</label>
            <input type="text" id="title" name="title" required value="<?= old('title', $form->title) ?>">

            <label for="form_key">Form Kodu</label>
            <input type="text" id="form_key" name="form_key" required value="<?= old('form_key', $form->form_key) ?>">

            <button type="submit" class="primary">Güncelle</button>
        </form>
    </article>

    <!-- Form Fields (Placeholder for future implementation) -->
    <article>
        <header><strong>Alanlar (Fields)</strong></header>
        <p>Buraya form alanlarını sürükle-bırak yöntemiyle ekleyeceğiniz yapı gelecek.</p>

        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-wrench fa-2x"></i><br>
            Alan Yönetimi Yakında...
        </div>
    </article>
</div>

<?= $this->endSection() ?>

<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni Form Oluştur</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/forms" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/App\Core\Modules\Form\Views\forms\store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="title">Form Başlığı</label>
                <input type="text" id="title" name="title" required value="<?= old('title') ?>"
                    placeholder="İletişim Formu">

                <label for="form_key">Form Kodu (Key)</label>
                <input type="text" id="form_key" name="form_key" required value="<?= old('form_key') ?>"
                    placeholder="iletisim_formu">
                <small>Bu kodu sitenizde formu çağırmak için kullanacaksınız.</small>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong>Bilgi</strong></header>
                    <p>Formu oluşturduktan sonra düzenleme ekranından form alanlarını (inputları) ekleyebilirsiniz.</p>

                    <button type="submit" class="primary" style="width: 100%;">
                        <i class="fa-solid fa-save"></i> Oluştur
                    </button>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>

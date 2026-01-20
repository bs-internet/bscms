<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni Ekle:
            <?= esc($contentType->title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-type/<?= $contentType->id ?>/contents" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/admin/contents/<?= $contentType->id ?>/store" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid">
            <!-- Main Content Area -->
            <div style="grid-column: span 2;">
                <label for="title">Başlık</label>
                <input type="text" id="title" name="title" required value="<?= old('title') ?>"
                    placeholder="İçerik başlığı">

                <label for="slug">Slug (URL)</label>
                <input type="text" id="slug" name="slug" value="<?= old('slug') ?>" placeholder="otomatik-olusturulur">

                <!-- Dynamic Fields -->
                <?php foreach ($fields as $field): ?>
                    <label for="<?= $field->field_key ?>">
                        <?= esc($field->title) ?>
                    </label>

                    <?php if ($field->field_type === 'text'): ?>
                        <input type="text" name="<?= $field->field_key ?>" id="<?= $field->field_key ?>"
                            value="<?= old($field->field_key) ?>">

                    <?php elseif ($field->field_type === 'textarea'): ?>
                        <textarea name="<?= $field->field_key ?>" id="<?= $field->field_key ?>"
                            rows="4"><?= old($field->field_key) ?></textarea>

                    <?php elseif ($field->field_type === 'editor'): ?>
                        <!-- Editor.js container or Standard Wysiwyg placeholder -->
                        <textarea name="<?= $field->field_key ?>" id="<?= $field->field_key ?>" class="rich-editor"
                            rows="10"><?= old($field->field_key) ?></textarea>

                    <?php elseif ($field->field_type === 'image'): ?>
                        <input type="file" name="<?= $field->field_key ?>" id="<?= $field->field_key ?>" class="filepond">

                    <?php elseif ($field->field_type === 'select'): ?>
                        <select name="<?= $field->field_key ?>" id="<?= $field->field_key ?>">
                            <option value="">Seçiniz</option>
                            <?php
                            $options = $field->getFieldOptions()['options'] ?? [];
                            // Parse options if they are string "key:value|key2:value2" or JSON
                            // Assuming simple array or newline separated for now based on typical CMS
                            // For this mockup, we assume standard select
                            ?>
                        </select>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>

            <!-- Sidebar / Meta -->
            <div>
                <article>
                    <header><strong>Yayın Ayarları</strong></header>

                    <label for="status">Durum</label>
                    <select name="status" id="status">
                        <?php foreach (\App\Enums\ContentStatus::cases() as $status): ?>
                            <option value="<?= $status->value ?>" <?= old('status') === $status->value ? 'selected' : '' ?>>
                                <?= $status->label() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if ($contentType->has_categories && !empty($categories)): ?>
                        <label for="categories">Kategoriler</label>
                        <select name="categories[]" id="categories" multiple style="height: 150px;">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= in_array($category->id, old('categories', [])) ? 'selected' : '' ?>>
                                    <?= esc($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
                        <i class="fa-solid fa-save"></i> Kaydet
                    </button>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>
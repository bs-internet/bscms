<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Content.edit_content', [esc($content->title)]) ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/contents/<?= $contentType->id ?>" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<article>
    <form action="/admin/contents/<?= $contentType->id ?>/update/<?= $content->id ?>" method="post"
        enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid">
            <!-- Main Content Area -->
            <div style="grid-column: span 2;">
                <label for="title"><?= lang('Content.title') ?></label>
                <input type="text" id="title" name="title" required value="<?= old('title', $content->title) ?>"
                    placeholder="<?= lang('Content.title') ?>">

                <label for="slug"><?= lang('Content.content_type_slug') ?></label>
                <input type="text" id="slug" name="slug" value="<?= old('slug', $content->slug) ?>"
                    placeholder="<?= lang('Admin.auto_generated') ?>">

                <!-- Dynamic Fields -->
                <?php foreach ($fields as $field): ?>
                    <?php
                    $val = old($field->field_key, $contentMeta[$field->field_key] ?? '');
                    ?>

                    <label for="<?= $field->field_key ?>">
                        <?= esc($field->title) ?>
                    </label>

                    <?php if ($field->field_type === 'text'): ?>
                        <input type="text" name="<?= $field->field_key ?>" id="<?= $field->field_key ?>"
                            value="<?= esc($val) ?>">

                    <?php elseif ($field->field_type === 'textarea'): ?>
                        <textarea name="<?= $field->field_key ?>" id="<?= $field->field_key ?>"
                            rows="4"><?= esc($val) ?></textarea>

                    <?php elseif ($field->field_type === 'editor'): ?>
                        <textarea name="<?= $field->field_key ?>" id="<?= $field->field_key ?>" class="rich-editor"
                            rows="10"><?= esc($val) ?></textarea>

                    <?php elseif ($field->field_type === 'image'): ?>
                        <?php if ($val): ?>
                            <div style="margin-bottom: 0.5rem;">
                                <img src="<?= $val ?>" alt="Current Image" style="max-height: 100px; border-radius: 4px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="<?= $field->field_key ?>" id="<?= $field->field_key ?>" class="filepond">

                    <?php elseif ($field->field_type === 'select'): ?>
                        <select name="<?= $field->field_key ?>" id="<?= $field->field_key ?>">
                            <option value=""><?= lang('Admin.select') ?></option>
                            <!-- Options logic would go here -->
                        </select>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>

            <!-- Sidebar / Meta -->
            <div>
                <article>
                    <header><strong><?= lang('Admin.publishing_settings') ?></strong></header>

                    <label for="status"><?= lang('Content.status') ?></label>
                    <select name="status" id="status">
                        <?php foreach (\App\Core\Modules\Content\Enums\ContentStatus::cases() as $status): ?>
                            <option value="<?= $status->value ?>" <?= old('status', $content->status) === $status->value ? 'selected' : '' ?>>
                                <?= $status->label() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if ($contentType->has_categories && !empty($categories)): ?>
                        <label for="categories"><?= lang('Content.category') ?></label>
                        <select name="categories[]" id="categories" multiple style="height: 150px;">
                            <?php foreach ($categories as $category): ?>
                                <?php
                                $isSelected = in_array($category->id, old('categories', $selectedCategories));
                                ?>
                                <option value="<?= $category->id ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= esc($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
                        <i class="fa-solid fa-save"></i> <?= lang('Admin.update') ?>
                    </button>

                    <div style="text-align: center; margin-top: 1rem;">
                        <small><?= lang('Admin.last_update') ?>:
                            <?= date('d.m.Y H:i', strtotime($content->updated_at ?? $content->created_at)) ?>
                        </small>
                    </div>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>
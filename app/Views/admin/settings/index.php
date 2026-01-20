<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Ayarlar</h1>
        <small>Sistem genel ayarlarını yapılandırın.</small>
    </div>
    <div style="text-align: right;">
        <!-- Optional Actions -->
    </div>
</div>

<article>
    <form action="/admin/settings/update" method="post">
        <?= csrf_field() ?>

        <?php if (empty($groupedSettings)): ?>
            <p>Ayarlanacak bir seçenek bulunamadı. Veritabanı seed çalıştırıldı mı?</p>
        <?php else: ?>
            <!-- Tabs (using Pico.css detail/summary or just sections) -->
            <!-- For simplicity in Pico, we'll use Fieldsets per group -->

            <?php foreach ($groupedSettings as $group => $settings): ?>
                <fieldset>
                    <legend><strong>
                            <?= strtoupper($group) ?>
                        </strong></legend>
                    <div class="grid">
                        <?php foreach ($settings as $setting): ?>
                            <div class="<?= count($settings) > 1 ? '' : 'grid-column-span-2' ?>">
                                <label for="<?= $setting->setting_group . '__' . $setting->setting_key ?>">
                                    <?= esc($setting->setting_key) ?>
                                </label>

                                <input type="text" name="<?= $setting->setting_group . '__' . $setting->setting_key ?>"
                                    id="<?= $setting->setting_group . '__' . $setting->setting_key ?>"
                                    value="<?= esc($setting->setting_value) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <hr>
            <?php endforeach; ?>

            <button type="submit" class="primary">
                <i class="fa-solid fa-save"></i> Ayarları Güncelle
            </button>
        <?php endif; ?>
    </form>
</article>

<?= $this->endSection() ?>
<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <article class="card stats-card" style="border-left: 4px solid var(--pico-primary-background);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small class="text-muted"><?= lang('System.total_contents') ?></small>
                    <h2 style="margin: 0;"><?= $totalContents ?></h2>
                </div>
                <div style="font-size: 2rem; opacity: 0.2;">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
        </article>
    </div>
    <div>
        <article class="card stats-card" style="border-left: 4px solid var(--pico-ins-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small class="text-muted"><?= lang('System.published_contents') ?></small>
                    <h2 style="margin: 0;"><?= $publishedContents ?></h2>
                </div>
                <div style="font-size: 2rem; opacity: 0.2; color: var(--pico-ins-color);">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </article>
    </div>
    <div>
        <article class="card stats-card" style="border-left: 4px solid var(--pico-muted-color);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <small class="text-muted"><?= lang('System.draft_contents') ?></small>
                    <h2 style="margin: 0;"><?= $draftContents ?></h2>
                </div>
                <div style="font-size: 2rem; opacity: 0.2;">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            </div>
        </article>
    </div>
</div>

<div class="grid">
    <div class="col-full">
        <article class="card">
            <header>
                <span><i class="fa-solid fa-clock-rotate-left" style="margin-right: 8px;" class="text-muted"></i>
                    <?= lang('System.recent_activities') ?></span>
            </header>
            <table>
                <thead>
                    <tr>
                        <th width="20%"><?= lang('System.user') ?></th>
                        <th width="50%"><?= lang('System.action') ?></th>
                        <th width="30%" style="text-align: right;"><?= lang('System.time') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div
                                    style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 12px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="font-medium">Admin</span>
                            </div>
                        </td>
                        <td><?= lang('System.updated_page', ['Hakkımızda']) ?></td>
                        <td style="text-align: right;" class="text-muted"><?= lang('Admin.hours_ago', [2]) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div
                                    style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 12px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="font-medium">Admin</span>
                            </div>
                        </td>
                        <td><?= lang('System.added_category', ['Teknoloji']) ?></td>
                        <td style="text-align: right;" class="text-muted"><?= lang('Admin.hours_ago', [5]) ?></td>
                    </tr>
                </tbody>
            </table>
        </article>
    </div>
</div>

<?= $this->endSection() ?>
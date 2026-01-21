<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

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
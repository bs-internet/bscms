<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div class="col-full">
        <article class="card">
            <header>
                <span><i class="fa-solid fa-clock-rotate-left" style="margin-right: 8px;" class="text-muted"></i> Son
                    Aktiviteler</span>
            </header>
            <table>
                <thead>
                    <tr>
                        <th width="20%">Kullanıcı</th>
                        <th width="50%">İşlem</th>
                        <th width="30%" style="text-align: right;">Zaman</th>
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
                        <td>'Hakkımızda' sayfasını güncelledi.</td>
                        <td style="text-align: right;" class="text-muted">2 saat önce</td>
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
                        <td>Yeni kategori ekledi: <strong>Teknoloji</strong></td>
                        <td style="text-align: right;" class="text-muted">5 saat önce</td>
                    </tr>
                </tbody>
            </table>
        </article>
    </div>
</div>

<?= $this->endSection() ?>

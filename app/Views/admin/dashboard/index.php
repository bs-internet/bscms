<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <article>
        <header><strong>İçerikler</strong></header>
        <div class="headings">
            <h1>120</h1>
            <small>Toplam İçerik</small>
        </div>
        <footer>
            <a href="/admin/content-type/1/contents" role="button" class="outline">Yönet</a>
        </footer>
    </article>

    <article>
        <header><strong>Kullanıcılar</strong></header>
        <div class="headings">
            <h1>45</h1>
            <small>Aktif Üye</small>
        </div>
        <footer>
            <a href="/admin/users" role="button" class="outline">Yönet</a>
        </footer>
    </article>

    <article>
        <header><strong>Formlar</strong></header>
        <div class="headings">
            <h1>12</h1>
            <small>Okunmamış Mesaj</small>
        </div>
        <footer>
            <a href="/admin/forms" role="button" class="outline">Görüntüle</a>
        </footer>
    </article>
</div>

<div class="grid" style="margin-top: 2rem;">
    <article>
        <header>Son Aktiviteler</header>
        <table class="striped">
            <thead>
                <tr>
                    <th scope="col">Kullanıcı</th>
                    <th scope="col">İşlem</th>
                    <th scope="col">Zaman</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Admin</td>
                    <td>'Hakkımızda' sayfasını güncelledi.</td>
                    <td>2 saat önce</td>
                </tr>
                <tr>
                    <td>Admin</td>
                    <td>Yeni kategori ekledi: 'Teknoloji'</td>
                    <td>5 saat önce</td>
                </tr>
            </tbody>
        </table>
    </article>
</div>

<?= $this->endSection() ?>
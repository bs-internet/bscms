<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Bileşenler (Components)</h1>
        <small>Sayfa ve içerikler için tekrar kullanılabilir bloklar.</small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/components/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> Yeni Bileşen
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">İsim</th>
                    <th scope="col">Kod (Key)</th>
                    <th scope="col">Global?</th>
                    <th scope="col" style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <!-- Mock Data or Empty State as Controller not fully read but structure implied -->
                <tr>
                    <td colspan="5" style="text-align: center;">Kayıt bulunamadı.</td>
                </tr>
            </tbody>
        </table>
    </figure>
    <!-- Note: Assuming ComponentController logic passes 'components' variable similar to other controllers -->
</article>

<?= $this->endSection() ?>
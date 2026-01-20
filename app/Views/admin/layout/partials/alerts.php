<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            toast("<?= session()->getFlashdata('success') ?>", 'success');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            toast("<?= session()->getFlashdata('error') ?>", 'error');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="article custom-alert error-alert">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

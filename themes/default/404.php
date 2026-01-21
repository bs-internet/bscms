<?php get_header(); ?>

<section class="container mx-auto px-4 py-20">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-blue-500 text-8xl"></i>
        </div>
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <h2 class="text-3xl font-bold text-gray-700 mb-6">Sayfa Bulunamadı</h2>
        <p class="text-gray-600 text-lg mb-8">
            Aradığınız sayfa mevcut değil veya taşınmış olabilir.
        </p>
        <a href="<?= base_url() ?>" 
           class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
            <i class="fas fa-home"></i>
            Ana Sayfaya Dön
        </a>
    </div>
</section>

<?php get_footer(); ?>
<?php get_header(); ?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-2">Blog & Haberler</h1>
        <p class="text-blue-100">Güncel haberler ve blog yazılarımız</p>
    </div>
</section>

<!-- Content -->
<section class="container mx-auto px-4 py-12">
    <?php if (have_posts()): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while (have_posts()): the_post(); ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <!-- Featured Image -->
                    <?php if ($featuredImage = get_meta('featured_image')): ?>
                        <div class="aspect-video bg-gray-200 overflow-hidden">
                            <img src="<?= esc(get_image($featuredImage, 'medium')) ?>" 
                                 alt="<?php the_title(); ?>"
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <!-- Categories -->
                        <?php if (has_categories()): ?>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <?php 
                                $categories = get_categories();
                                foreach ($categories as $category): 
                                ?>
                                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                        <?= esc($category->name) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Title -->
                        <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        
                        <!-- Excerpt -->
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?php the_excerpt(150); ?>
                        </p>
                        
                        <!-- Read More -->
                        <a href="<?php the_permalink(); ?>" 
                           class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
                            Devamını Oku 
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Henüz içerik yok</h3>
            <p class="text-gray-500">Yakında yeni içerikler eklenecek.</p>
        </div>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
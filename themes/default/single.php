<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <!-- Article Header -->
        <article class="bg-white">
            <!-- Featured Image -->
            <?php if ($featuredImage = get_meta('featured_image')): ?>
                <div class="w-full h-96 bg-gray-200 overflow-hidden">
                    <img src="<?= esc(get_image($featuredImage, 'large')) ?>" 
                         alt="<?php the_title(); ?>"
                         class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            
            <div class="container mx-auto px-4 py-12">
                <div class="max-w-4xl mx-auto">
                    <!-- Categories & Date -->
                    <div class="flex items-center justify-between mb-6">
                        <?php if (has_categories()): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                $categories = get_categories();
                                foreach ($categories as $category): 
                                ?>
                                    <span class="text-sm font-medium text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full">
                                        <?= esc($category->name) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <time class="text-sm text-gray-500">
                            <i class="far fa-calendar mr-2"></i>
                            <?= date('d.m.Y', strtotime(get_meta('created_at') ?: date('Y-m-d'))) ?>
                        </time>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Content -->
                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Paylaş</h3>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" 
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode(get_the_title()) ?>" 
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition">
                                <i class="fab fa-twitter"></i>
                                Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode(get_the_title()) ?>" 
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
                                <i class="fab fa-linkedin-in"></i>
                                LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
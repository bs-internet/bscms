<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <!-- Page Header -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold"><?php the_title(); ?></h1>
            </div>
        </section>
        
        <!-- Page Content -->
        <section class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-8 md:p-12">
                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
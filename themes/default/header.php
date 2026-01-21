<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc(get_seo_title()) ?></title>
    <meta name="description" content="<?= esc(get_seo_description()) ?>">
    <?= get_head_tags() ?>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('themes/default/css/style.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php the_body_class(); ?> bg-gray-50">
    
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?= base_url() ?>" class="text-2xl font-bold text-blue-600">
                        <?= esc(get_setting('site_title', 'BSCMS')) ?>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <?php 
                    $menu = get_menu('header');
                    if (!empty($menu)):
                        foreach ($menu as $item): 
                    ?>
                        <a href="<?= esc($item['url']) ?>" 
                           class="text-gray-700 hover:text-blue-600 font-medium transition">
                            <?= esc($item['title']) ?>
                        </a>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                    <a href="/iletisim" 
                       class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium">
                        İletişim
                    </a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <nav id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4 space-y-3">
                <?php 
                if (!empty($menu)):
                    foreach ($menu as $item): 
                ?>
                    <a href="<?= esc($item['url']) ?>" 
                       class="block text-gray-700 hover:text-blue-600 font-medium py-2">
                        <?= esc($item['title']) ?>
                    </a>
                <?php 
                    endforeach;
                endif;
                ?>
                <a href="/iletisim" 
                   class="block bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium text-center">
                    İletişim
                </a>
            </div>
        </nav>
    </header>
    
    <main>
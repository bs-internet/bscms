</main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Hakkımızda -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">
                        <?= esc(get_setting('site_title', 'BSCMS')) ?>
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        <?= esc(get_setting('site_description', 'Kurumsal içerik yönetim sistemi')) ?>
                    </p>
                </div>
                
                <!-- Hızlı Linkler -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Hızlı Linkler</h3>
                    <ul class="space-y-2">
                        <?php 
                        $footerMenu = get_menu('footer');
                        if (!empty($footerMenu)):
                            foreach ($footerMenu as $item): 
                        ?>
                            <li>
                                <a href="<?= esc($item['url']) ?>" 
                                   class="text-gray-400 hover:text-white transition text-sm">
                                    <?= esc($item['title']) ?>
                                </a>
                            </li>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                
                <!-- İletişim -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">İletişim</h3>
                    <ul class="space-y-3">
                        <?php if (get_setting('contact_address')): ?>
                        <li class="flex items-start text-sm">
                            <i class="fas fa-map-marker-alt text-blue-500 mt-1 mr-3"></i>
                            <span class="text-gray-400"><?= esc(get_setting('contact_address')) ?></span>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (get_setting('contact_phone')): ?>
                        <li class="flex items-center text-sm">
                            <i class="fas fa-phone text-blue-500 mr-3"></i>
                            <a href="tel:<?= esc(get_setting('contact_phone')) ?>" 
                               class="text-gray-400 hover:text-white transition">
                                <?= esc(get_setting('contact_phone')) ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (get_setting('contact_email')): ?>
                        <li class="flex items-center text-sm">
                            <i class="fas fa-envelope text-blue-500 mr-3"></i>
                            <a href="mailto:<?= esc(get_setting('contact_email')) ?>" 
                               class="text-gray-400 hover:text-white transition">
                                <?= esc(get_setting('contact_email')) ?>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Sosyal Medya -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Bizi Takip Edin</h3>
                    <div class="flex space-x-4">
                        <?php if (get_setting('social_facebook')): ?>
                        <a href="<?= esc(get_setting('social_facebook')) ?>" 
                           target="_blank"
                           class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_setting('social_twitter')): ?>
                        <a href="<?= esc(get_setting('social_twitter')) ?>" 
                           target="_blank"
                           class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_setting('social_instagram')): ?>
                        <a href="<?= esc(get_setting('social_instagram')) ?>" 
                           target="_blank"
                           class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_setting('social_linkedin')): ?>
                        <a href="<?= esc(get_setting('social_linkedin')) ?>" 
                           target="_blank"
                           class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; <?= date('Y') ?> <?= esc(get_setting('site_title', 'BSCMS')) ?>. 
                    Tüm hakları saklıdır. 
                    <span class="text-gray-600">|</span> 
                    Powered by <a href="https://bscms.com" class="text-blue-500 hover:text-blue-400">BSCMS</a>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?= base_url('themes/default/js/main.js') ?>"></script>
</body>
</html>
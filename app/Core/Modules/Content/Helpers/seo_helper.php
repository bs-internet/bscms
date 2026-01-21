<?php

if (!function_exists('get_seo_title')) {
    function get_seo_title(): string
    {
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            $seoTitle = $loop->getMeta('seo_title');
            if ($seoTitle) {
                return $seoTitle;
            }
            return $currentContent->title . ' - ' . get_setting('site_title', 'BSCMS');
        }
        
        return get_setting('site_title', 'BSCMS');
    }
}

if (!function_exists('get_seo_description')) {
    function get_seo_description(): ?string
    {
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            $seoDescription = $loop->getMeta('seo_description');
            if ($seoDescription) {
                return $seoDescription;
            }
            
            $content = $loop->getMeta('content');
            if ($content) {
                return substr(strip_tags($content), 0, 160) . '...';
            }
        }
        
        return get_setting('site_description', null);
    }
}

if (!function_exists('get_seo_image')) {
    function get_seo_image(): ?string
    {
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            $seoImageId = $loop->getMeta('seo_image');
            if ($seoImageId) {
                return get_image($seoImageId);
            }
            
            $gorselId = $loop->getMeta('gorsel');
            if ($gorselId) {
                return get_image($gorselId);
            }
        }
        
        $defaultLogo = get_setting('logo');
        if ($defaultLogo) {
            return base_url('uploads/' . $defaultLogo);
        }
        
        return null;
    }
}

if (!function_exists('get_canonical_url')) {
    function get_canonical_url(): string
    {
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            return get_the_permalink();
        }
        
        return current_url();
    }
}

if (!function_exists('get_favicon')) {
    function get_favicon(): ?string
    {
        $favicon = get_setting('favicon');
        
        if ($favicon) {
            return base_url('uploads/' . $favicon);
        }
        
        return null;
    }
}

if (!function_exists('get_head_tags')) {
    function get_head_tags(): string
    {
        $html = '';
        
        // Favicon
        $favicon = get_favicon();
        if ($favicon) {
            $html .= '<link rel="icon" type="image/x-icon" href="' . esc($favicon) . '">' . "\n";
        }
        
        // Title
        $title = get_seo_title();
        $html .= '<title>' . esc($title) . '</title>' . "\n";
        
        // Description
        $description = get_seo_description();
        if ($description) {
            $html .= '<meta name="description" content="' . esc($description) . '">' . "\n";
        }
        
        // Canonical URL
        $canonicalUrl = get_canonical_url();
        $html .= '<link rel="canonical" href="' . esc($canonicalUrl) . '">' . "\n";
        
        // Open Graph Tags
        $html .= '<meta property="og:type" content="website">' . "\n";
        $html .= '<meta property="og:title" content="' . esc($title) . '">' . "\n";
        
        if ($description) {
            $html .= '<meta property="og:description" content="' . esc($description) . '">' . "\n";
        }
        
        $html .= '<meta property="og:url" content="' . esc($canonicalUrl) . '">' . "\n";
        
        $image = get_seo_image();
        if ($image) {
            $html .= '<meta property="og:image" content="' . esc($image) . '">' . "\n";
        }
        
        $siteName = get_setting('site_title', 'BSCMS');
        $html .= '<meta property="og:site_name" content="' . esc($siteName) . '">' . "\n";
        
        // Twitter Card Tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . esc($title) . '">' . "\n";
        
        if ($description) {
            $html .= '<meta name="twitter:description" content="' . esc($description) . '">' . "\n";
        }
        
        if ($image) {
            $html .= '<meta name="twitter:image" content="' . esc($image) . '">' . "\n";
        }
        
        // Schema Markup
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_setting('site_title', 'BSCMS'),
            'url' => base_url()
        ];
        
        if ($currentContent) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $currentContent->title,
                'description' => $description,
                'url' => $canonicalUrl
            ];
            
            if ($image) {
                $schema['image'] = $image;
            }
        }
        
        $html .= '<script type="application/ld+json">' . "\n";
        $html .= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
        $html .= '</script>' . "\n";
        
        // Google Analytics
        $googleAnalytics = get_setting('google_analytics');
        if ($googleAnalytics) {
            $html .= "\n" . '<!-- Google Analytics -->' . "\n";
            $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc($googleAnalytics) . '"></script>' . "\n";
            $html .= '<script>' . "\n";
            $html .= '  window.dataLayer = window.dataLayer || [];' . "\n";
            $html .= '  function gtag(){dataLayer.push(arguments);}' . "\n";
            $html .= '  gtag(\'js\', new Date());' . "\n";
            $html .= '  gtag(\'config\', \'' . esc($googleAnalytics) . '\');' . "\n";
            $html .= '</script>' . "\n";
        }
        
        // Google Tag Manager
        $googleTagManager = get_setting('google_tag_manager');
        if ($googleTagManager) {
            $html .= "\n" . '<!-- Google Tag Manager -->' . "\n";
            $html .= '<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':' . "\n";
            $html .= 'new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],' . "\n";
            $html .= 'j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=' . "\n";
            $html .= '\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);' . "\n";
            $html .= '})(window,document,\'script\',\'dataLayer\',\'' . esc($googleTagManager) . '\');</script>' . "\n";
        }
        
        return $html;
    }
}

if (!function_exists('the_head_tags')) {
    function the_head_tags(): void
    {
        echo get_head_tags();
    }
}

if (!function_exists('get_gtm_body_tag')) {
    function get_gtm_body_tag(): string
    {
        $googleTagManager = get_setting('google_tag_manager');
        
        if (!$googleTagManager) {
            return '';
        }
        
        $html = '<!-- Google Tag Manager (noscript) -->' . "\n";
        $html .= '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . esc($googleTagManager) . '"' . "\n";
        $html .= 'height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
        $html .= '<!-- End Google Tag Manager (noscript) -->';
        
        return $html;
    }
}

if (!function_exists('the_gtm_body_tag')) {
    function the_gtm_body_tag(): void
    {
        echo get_gtm_body_tag();
    }
}

if (!function_exists('get_breadcrumb')) {
    function get_breadcrumb(): array
    {
        $breadcrumb = [];
        
        $breadcrumb[] = [
            'title' => get_setting('site_title', 'Ana Sayfa'),
            'url' => base_url()
        ];
        
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            $categories = $loop->getCategories();
            
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumb[] = [
                    'title' => $category->name,
                    'url' => site_url('category/' . $category->slug)
                ];
            }
            
            $breadcrumb[] = [
                'title' => $currentContent->title,
                'url' => null
            ];
        }
        
        return $breadcrumb;
    }
}

if (!function_exists('the_breadcrumb')) {
    function the_breadcrumb(string $separator = ' / ', string $class = 'breadcrumb'): void
    {
        $breadcrumb = get_breadcrumb();
        
        if (empty($breadcrumb)) {
            return;
        }
        
        echo '<nav class="' . esc($class) . '">';
        
        $total = count($breadcrumb);
        foreach ($breadcrumb as $index => $item) {
            $isLast = ($index === $total - 1);
            
            if ($isLast || !$item['url']) {
                echo '<span>' . esc($item['title']) . '</span>';
            } else {
                echo '<a href="' . esc($item['url']) . '">' . esc($item['title']) . '</a>';
            }
            
            if (!$isLast) {
                echo '<span class="separator">' . $separator . '</span>';
            }
        }
        
        echo '</nav>';
    }
}
<?php

/**
 * SEO Optimizer Plugin Configuration
 */
return [
    'name' => 'SEO Optimizer',
    'slug' => 'seo-optimizer',
    'version' => '1.0.0',
    'author' => 'BSCMS Team',
    'author_url' => 'https://bscms.dev',
    'description' => 'Automatically optimize your content for search engines with meta tags, SEO scores, and sitemap generation.',

    'requires' => [
        'bscms' => '>=1.0.0',
        'php' => '>=8.1'
    ],

    'dependencies' => [
        // Other plugins this depends on
    ],

    'hooks' => [
        'content.before_save' => 'Optimize SEO fields before saving',
        'content.list' => 'Add SEO score to content list',
    ],

    'events' => [
        'ContentCreated' => 'Regenerate sitemap',
        'ContentUpdated' => 'Regenerate sitemap',
        'ContentDeleted' => 'Regenerate sitemap',
    ],

    'settings' => [
        'auto_generate_meta' => true,
        'sitemap_auto_update' => true,
        'min_content_length' => 300,
    ],
];

<?php

namespace App\Plugins\SEOOptimizer;

use App\Core\Shared\Libraries\Plugin as BasePlugin;

/**
 * SEO Optimizer Plugin
 * 
 * Automatically optimizes content for search engines by:
 * - Generating meta titles and descriptions
 * - Adding SEO scores to content
 * - Generating sitemaps on content changes
 */
class Plugin extends BasePlugin
{
    protected string $name = 'SEO Optimizer';
    protected string $version = '1.0.0';
    protected string $author = 'BSCMS Team';
    protected string $description = 'Optimize your content for search engines automatically';

    /**
     * Register hooks and event listeners
     */
    public function register(): void
    {
        // Hook into content save to optimize SEO fields
        $this->addHook('content.before_save', [$this, 'optimizeSEO'], 5);

        // Hook into content list to add SEO score column
        $this->addHook('content.list', [$this, 'addSEOScoreColumn'], 10);

        // Listen to content events for sitemap generation
        $this->listen('ContentCreated', [$this, 'onContentCreated']);
        $this->listen('ContentUpdated', [$this, 'onContentUpdated']);
        $this->listen('ContentDeleted', [$this, 'onContentDeleted']);
    }

    /**
     * Boot the plugin
     */
    public function boot(): void
    {
        log_message('info', 'SEO Optimizer Plugin booted');
    }

    /**
     * Optimize SEO fields before saving content
     * 
     * @param array $content Content data
     * @return array Modified content data
     */
    public function optimizeSEO(array $content): array
    {
        // Auto-generate meta title if not provided
        if (empty($content['meta_title']) && !empty($content['title'])) {
            $content['meta_title'] = $content['title'];
        }

        // Auto-generate meta description if not provided
        if (empty($content['meta_description']) && !empty($content['content'])) {
            $content['meta_description'] = $this->generateMetaDescription($content['content']);
        }

        // Add SEO score
        $content['seo_score'] = $this->calculateSEOScore($content);

        return $content;
    }

    /**
     * Add SEO score column to content list
     * 
     * @param array $contents Content list
     * @return array Modified content list
     */
    public function addSEOScoreColumn(array $contents): array
    {
        foreach ($contents as &$content) {
            if (!isset($content->seo_score)) {
                $content->seo_score = $this->calculateSEOScore((array) $content);
            }
        }

        return $contents;
    }

    /**
     * Handle ContentCreated event
     * 
     * @param object $content
     */
    public function onContentCreated($content): void
    {
        log_message('info', "SEO Optimizer: Content created - {$content->title}");
        $this->regenerateSitemap();
    }

    /**
     * Handle ContentUpdated event
     * 
     * @param object $content
     */
    public function onContentUpdated($content): void
    {
        log_message('info', "SEO Optimizer: Content updated - {$content->title}");
        $this->regenerateSitemap();
    }

    /**
     * Handle ContentDeleted event
     * 
     * @param object $content
     */
    public function onContentDeleted($content): void
    {
        log_message('info', "SEO Optimizer: Content deleted - {$content->title}");
        $this->regenerateSitemap();
    }

    /**
     * Generate meta description from content
     * 
     * @param string $content
     * @return string
     */
    private function generateMetaDescription(string $content): string
    {
        // Strip HTML tags
        $text = strip_tags($content);

        // Limit to 160 characters
        if (strlen($text) > 160) {
            $text = substr($text, 0, 157) . '...';
        }

        return $text;
    }

    /**
     * Calculate SEO score for content
     * 
     * @param array $content
     * @return int Score from 0-100
     */
    private function calculateSEOScore(array $content): int
    {
        $score = 0;

        // Has title (20 points)
        if (!empty($content['title'])) {
            $score += 20;
        }

        // Has meta title (15 points)
        if (!empty($content['meta_title'])) {
            $score += 15;
        }

        // Has meta description (15 points)
        if (!empty($content['meta_description'])) {
            $score += 15;
        }

        // Meta description length is good (10 points)
        if (
            !empty($content['meta_description']) &&
            strlen($content['meta_description']) >= 120 &&
            strlen($content['meta_description']) <= 160
        ) {
            $score += 10;
        }

        // Has content (20 points)
        if (!empty($content['content']) && strlen($content['content']) > 300) {
            $score += 20;
        }

        // Has slug (10 points)
        if (!empty($content['slug'])) {
            $score += 10;
        }

        // Has featured image (10 points)
        if (!empty($content['featured_image'])) {
            $score += 10;
        }

        return min(100, $score);
    }

    /**
     * Regenerate sitemap
     */
    private function regenerateSitemap(): void
    {
        // Trigger sitemap regeneration
        // This would call the sitemap controller or service
        log_message('info', 'SEO Optimizer: Sitemap regeneration triggered');
    }
}

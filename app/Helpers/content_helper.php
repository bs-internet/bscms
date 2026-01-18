<?php

if (!function_exists('have_posts')) {
    function have_posts(): bool
    {
        $loop = service('loop');
        return $loop->havePosts();
    }
}

if (!function_exists('the_post')) {
    function the_post(): void
    {
        $loop = service('loop');
        $loop->thePost();
    }
}

if (!function_exists('rewind_posts')) {
    function rewind_posts(): void
    {
        $loop = service('loop');
        $loop->rewindPosts();
    }
}

if (!function_exists('the_title')) {
    function the_title(): void
    {
        echo esc(get_the_title());
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title(): ?string
    {
        $loop = service('loop');
        return $loop->getTitle();
    }
}

if (!function_exists('the_content')) {
    function the_content(): void
    {
        echo get_the_content();
    }
}

if (!function_exists('get_the_content')) {
    function get_the_content(): ?string
    {
        return get_meta('content');
    }
}

if (!function_exists('the_permalink')) {
    function the_permalink(): void
    {
        echo esc(get_the_permalink());
    }
}

if (!function_exists('get_the_permalink')) {
    function get_the_permalink(): ?string
    {
        $loop = service('loop');
        return $loop->getPermalink();
    }
}

if (!function_exists('the_excerpt')) {
    function the_excerpt(int $length = 150): void
    {
        echo esc(get_the_excerpt($length));
    }
}

if (!function_exists('get_the_excerpt')) {
    function get_the_excerpt(int $length = 150): ?string
    {
        $loop = service('loop');
        return $loop->getExcerpt($length);
    }
}

if (!function_exists('get_meta')) {
    function get_meta(string $key)
    {
        $loop = service('loop');
        return $loop->getMeta($key);
    }
}

if (!function_exists('the_meta')) {
    function the_meta(string $key): void
    {
        echo esc(get_meta($key));
    }
}

if (!function_exists('get_repeater')) {
    function get_repeater(string $key): ?array
    {
        $loop = service('loop');
        return $loop->getRepeater($key);
    }
}

if (!function_exists('get_image')) {
    function get_image(int $mediaId, string $size = 'full'): ?string
    {
        $loop = service('loop');
        return $loop->getImage($mediaId, $size);
    }
}

if (!function_exists('the_image')) {
    function the_image(int $mediaId, string $size = 'full'): void
    {
        $url = get_image($mediaId, $size);
        if ($url) {
            echo esc($url);
        }
    }
}

if (!function_exists('get_gallery')) {
    function get_gallery(array $mediaIds): array
    {
        $loop = service('loop');
        return $loop->getGallery($mediaIds);
    }
}

if (!function_exists('get_relation')) {
    function get_relation(string $key)
    {
        $loop = service('loop');
        return $loop->getRelation($key);
    }
}

if (!function_exists('get_relations')) {
    function get_relations(string $key): array
    {
        $loop = service('loop');
        return $loop->getRelations($key);
    }
}

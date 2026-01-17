<?php

if (!function_exists('get_header')) {
    function get_header(array $data = []): void
    {
        $template = service('template');
        echo $template->loadHeader($data);
    }
}

if (!function_exists('get_footer')) {
    function get_footer(array $data = []): void
    {
        $template = service('template');
        echo $template->loadFooter($data);
    }
}

if (!function_exists('get_body_class')) {
    function get_body_class(): string
    {
        $classes = [];
        
        $loop = service('loop');
        $currentContent = $loop->getCurrentContent();
        
        if ($currentContent) {
            $classes[] = 'page';
            $classes[] = 'page-' . $currentContent->slug;
            $classes[] = 'page-id-' . $currentContent->id;
            
            $contentTypeRepository = service('contentTypeRepository');
            $contentType = $contentTypeRepository->findById($currentContent->content_type_id);
            
            if ($contentType) {
                $classes[] = 'content-type-' . $contentType->slug;
            }
        } else {
            $classes[] = 'home';
        }
        
        return implode(' ', $classes);
    }
}

if (!function_exists('the_body_class')) {
    function the_body_class(): void
    {
        echo get_body_class();
    }
}
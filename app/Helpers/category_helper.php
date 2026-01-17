<?php

if (!function_exists('get_categories')) {
    function get_categories(): array
    {
        $loop = service('loop');
        return $loop->getCategories();
    }
}

if (!function_exists('the_categories')) {
    function the_categories(string $separator = ', '): void
    {
        $categories = get_categories();
        $names = array_map(fn($cat) => $cat->name, $categories);
        echo implode($separator, $names);
    }
}

if (!function_exists('has_category')) {
    function has_category(int $categoryId): bool
    {
        $loop = service('loop');
        return $loop->hasCategory($categoryId);
    }
}

if (!function_exists('get_all_categories')) {
    function get_all_categories(string $contentTypeSlug): array
    {
        $contentTypeRepository = service('contentTypeRepository');
        $categoryRepository = service('categoryRepository');
        
        $contentType = $contentTypeRepository->findBySlug($contentTypeSlug);
        
        if (!$contentType) {
            return [];
        }
        
        return $categoryRepository->getByContentTypeId($contentType->id);
    }
}
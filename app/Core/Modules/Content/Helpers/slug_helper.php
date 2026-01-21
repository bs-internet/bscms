<?php

if (!function_exists('generate_slug')) {
    function generate_slug(string $text): string
    {
        $turkish = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $english = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        
        $text = str_replace($turkish, $english, $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        
        return $text;
    }
}

if (!function_exists('generate_unique_slug')) {
    function generate_unique_slug(string $text, string $table, ?int $excludeId = null): string
    {
        $db = \Config\Database::connect();
        $slug = generate_slug($text);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $builder = $db->table($table)->where('slug', $slug);
            
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            
            $result = $builder->get()->getRow();
            
            if (!$result) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('auto_generate_slug')) {
    function auto_generate_slug(string $title, string $table, ?string $currentSlug = null, ?int $id = null): string
    {
        if ($currentSlug && !empty(trim($currentSlug))) {
            return generate_slug($currentSlug);
        }
        
        return generate_unique_slug($title, $table, $id);
    }
}
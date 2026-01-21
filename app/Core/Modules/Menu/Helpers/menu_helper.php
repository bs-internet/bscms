<?php

if (!function_exists('get_menu')) {
    function get_menu(string $location): array
    {
        $template = service('template');
        return $template->getMenu($location);
    }
}

if (!function_exists('wp_nav_menu')) {
    function wp_nav_menu(string $location, string $ulClass = '', string $liClass = ''): void
    {
        $menu = get_menu($location);
        
        if (empty($menu)) {
            return;
        }
        
        echo '<ul' . ($ulClass ? ' class="' . $ulClass . '"' : '') . '>';
        render_menu_items($menu, $liClass);
        echo '</ul>';
    }
}

if (!function_exists('render_menu_items')) {
    function render_menu_items(array $items, string $liClass = ''): void
    {
        foreach ($items as $item) {
            echo '<li' . ($liClass ? ' class="' . $liClass . '"' : '') . '>';
            echo '<a href="' . $item['url'] . '">' . $item['title'] . '</a>';
            
            if (!empty($item['children'])) {
                echo '<ul>';
                render_menu_items($item['children'], $liClass);
                echo '</ul>';
            }
            
            echo '</li>';
        }
    }
}
<?php

namespace BuyMeCoffee\Classes;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class View
{

    /**
     * Generate and echo/print a view file
     * @param string $path
     * @param array $data
     * @return void
     */
    public static function render($path, $data = [])
    {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo static::make($path, $data);
    }

    /**
     * Generate a view file
     * @param string $path
     * @param array $data
     * @return string [generated html]
     */
    public static function make($path, $data = [])
    {
        if (file_exists($path = self::getFilePath($path))) {
            ob_start();
            extract($data);
            include $path;
            return ob_get_clean();
        }
        return '';
    }

    /**
     * Resolve the view file path
     * @param string $path
     * @return string
     */
    protected static function getFilePath($path)
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        $viewName = BUYMECOFFEE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $path;
        $fullPath = $viewName . '.php';
        return apply_filters('buymecoffee/template_view_path', $fullPath, $path);
    }
}
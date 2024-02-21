<?php
defined('ABSPATH') or die;
// Autoload plugin.
require 'autoload.php';
if (! function_exists('buyMeCoffeeDB')) {
    /**
     * @return \WpFluent\QueryBuilder\QueryBuilderHandler
     */
    function buyMeCoffeeDB() {
        static $buyMeCoffeeDB;
        if (! $buyMeCoffeeDB) {
            global $wpdb;
            $connection = new WpFluent\Connection($wpdb, ['prefix' => $wpdb->prefix]);
            $buyMeCoffeeDB = new \WpFluent\QueryBuilder\QueryBuilderHandler($connection);
        }
        return $buyMeCoffeeDB;
    }
}

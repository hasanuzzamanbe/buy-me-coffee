<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Imagetoolbar" content="No"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e('Buy Me Coffee', 'buy-me-coffee') ?></title>
    <?php
    wp_head();
    ?>
    <?php

    include WPM_BMC_DIR . 'includes/views/templates/FormTemplate.php';

    wp_footer();
    ?>
    </head>
</html>
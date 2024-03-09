<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Imagetoolbar" content="No"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url($profile_image)?>">
    <title> <?php esc_html_e('Buy Me Coffee', 'buy-me-coffee') ?></title>
    <?php
    wp_head();
    ?>
    <?php
    if (current_user_can('manage_options')):
        ?>
        <div class="wrapper">
            <ul class="tab__content">
                <li class="active">
                    <div class="content__wrapper">
                        <ul class="colors">
                            <li data-color="rgb(255, 129, 63)"></li>
                            <li data-color="rgb(255, 95, 95)"></li>
                            <li data-color="rgb(95, 127, 255)"></li>
                            <li data-color="rgb(137, 239, 232)" class="active-color"></li>
                            <li data-color="rgb(189, 95, 255)"></li>
                            <li data-color="rgb(244, 113, 255)"></li>
                            <li data-color="rgb(38, 176, 161)"></li>
                            <li data-color="rgb(253, 181, 0)"></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div>
                <button class="save_changes"> Save Changes</button>
            </div>
        </div>
    <?php
    endif;

    include BUYMECOFFEE_DIR . 'includes/views/templates/FormTemplate.php';

    wp_footer();
    ?>
    </head>
</html>
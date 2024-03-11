<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
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
        <div class="buymecoffee_customizer_menu">
            <p>customizer</p>
            <svg fill="#545454" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="18" height="18"><path d="M1,4.75H3.736a3.728,3.728,0,0,0,7.195,0H23a1,1,0,0,0,0-2H10.931a3.728,3.728,0,0,0-7.195,0H1a1,1,0,0,0,0,2ZM7.333,2a1.75,1.75,0,1,1-1.75,1.75A1.752,1.752,0,0,1,7.333,2Z"/><path d="M23,11H20.264a3.727,3.727,0,0,0-7.194,0H1a1,1,0,0,0,0,2H13.07a3.727,3.727,0,0,0,7.194,0H23a1,1,0,0,0,0-2Zm-6.333,2.75A1.75,1.75,0,1,1,18.417,12,1.752,1.752,0,0,1,16.667,13.75Z"/><path d="M23,19.25H10.931a3.728,3.728,0,0,0-7.195,0H1a1,1,0,0,0,0,2H3.736a3.728,3.728,0,0,0,7.195,0H23a1,1,0,0,0,0-2ZM7.333,22a1.75,1.75,0,1,1,1.75-1.75A1.753,1.753,0,0,1,7.333,22Z"/></svg>
        </div>
        <div class="buymecoffee_customizer_wrapper" style="display: none;">
            <div class="buymecoffee_color_wrapper">
                <ul class="buymecoffee_colors">
                    <li data-color="rgb(255, 129, 63)"></li>
                    <li data-color="rgb(255, 95, 95)"></li>
                    <li data-color="rgb(95, 127, 255)"></li>
                    <li data-color="rgb(137, 239, 232)"></li>
                    <li data-color="rgb(189, 95, 255)"></li>
                    <li data-color="rgb(244, 113, 255)"></li>
                    <li data-color="rgb(38, 176, 161)"></li>
                    <li data-color="rgb(253, 181, 0)"></li>
                </ul>
            </div>
            <div>
                <button id="buymecoffee_save_changes"> Save Changes</button>
            </div>
        </div>
    <?php
    endif;

    include BUYMECOFFEE_DIR . 'includes/views/templates/FormTemplate.php';

    wp_footer();
    ?>
    </head>
</html>
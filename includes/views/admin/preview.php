<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Imagetoolbar" content="No"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e('Buy Me Coffee', 'buymecoffee') ?></title>
    <?php
    wp_head();
    ?>
    <style type="text/css">
        .buymecoffee_preview_body {
            padding: 40px 0px 40px 0px;
            width: 100%;
            min-height: 85vh;
        }

        .buymecoffee_form_preview_wrapper {
            padding: 30px;
            /* width: 60%; */
            max-width: 900px;
            background: white;
            padding: 23px;
            margin: auto;
        }

        .buymecoffee_preview_header {
            top: 0px;
            left: 0;
            right: 0px;
            padding: 0px 20px 0px 0px;
            background-color: #ebedee;
            color: black;
            font-family: cursive;
        }

        .buymecoffee_preview_header h3 {
            padding:0px 23px;
        }

        .buymecoffee_preview_footer {
            padding: 10px;
            text-align: right;
            font-family: cursive;
        }

    </style>
</head>
<body>
<div id="buymecoffee_preview_top">
    <div class="buymecoffee_preview_header">
        <h3><?php esc_html_e('Buy Me Coffee Button preview:', 'buymecoffee') ?></h3>
    </div>
    <div class="buymecoffee_preview_body">
        <div class="buymecoffee_form_preview_wrapper">
            <?php echo do_shortcode('[buymecoffee type="button"]'); ?>
        </div>
    </div>
    <div class="buymecoffee_preview_footer">
        copyright @buymecoffee
    </div>
</div>
<?php
wp_footer();
?>

<script type="text/javascript">

</script>

</body>
</html>
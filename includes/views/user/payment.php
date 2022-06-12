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
            max-width: 555px;
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
        }

        .buymecoffee_preview_header h3 {
            padding:0px 23px;
        }

        .buymecoffee_preview_footer {
            padding: 10px;
            text-align: right;
        }
        .wpm_bmc_input_content input,
        .wpm_bmc_input_content textarea {
            width: 100% !important;
            padding: 4px;
            border: 1px solid #d1d3da;
            border-radius: 4px;
            resize: vertical;
            box-shadow: none;
            background: white !important;
        }
        .wpm_bmc_input_content input:focus,
        .wpm_bmc_input_content textarea:focus {
            outline: none;
            box-shadow: none;
        }

        .wpm_bmc_form_item {
            margin-bottom: 14px;
        }

        .wpm_bmc_payment_item {
            border: 1px solid #ffe3b9;
            background: #fff2e059;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 6px;
        }
        .wpm_bmc_currency_prefix {
            font-size: 33px;
            background: #ff9800;
            border: 1px solid #ff9800;
            color: white;
            width: 41px;
            text-align: center;
        }
        .wpm_bmc_input_content ::placeholder {
            color: #ccc;
            font-size: 12px;
        }
        .wpm_submit_button {
            width: 100%;
            border-radius: 4px;
            margin-top: 12px;
        }
    </style>
</head>
<body>
<div id="buymecoffee_preview_top">
    <div class="buymecoffee_preview_header">
        <h3><?php esc_html_e('', 'buymecoffee') ?></h3>
    </div>
    <div class="buymecoffee_preview_body">
        <div class="buymecoffee_form_preview_wrapper">
            <div style="display: flex;">
                <span style="font-size:30px;margin-right: 23px;line-height: 40px;"
                    class="dashicons dashicons-coffee"></span>
                <h3 style="font-family: cursive;">Thanks for your appreciation.</h3>
            </div>

            <hr>
            <?php
                $form =  \buyMeCoffee\Builder\Render::renderInputElements();
                echo $form;
            ?>
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
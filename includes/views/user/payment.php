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
    <style type="text/css" lang="scss">
        .buymecoffee_preview_body {
            width: 100%;
        }

        .bmc_appreciation_title {
            font-weight: 100;
            margin: 0;
        }
        .buymecoffee_form_preview_wrapper {
            max-width: 555px;
            background: white;
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
            font-size: 13px;
            color: #a0a0a0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: italic;
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
            height: 33px;
        }
        .wpm_bmc_input_content input:focus,
        .wpm_bmc_input_content textarea:focus {
            outline: none;
            box-shadow: none;
        }

        .wpm_bmc_form_item {
            margin-bottom: 14px;
        }

        .wpm_bmc_currency_prefix {
            font-size: 33px;
            background: #000000;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            color: white;
            width: 59px;
            height: 62px;
            text-align: center;
        }
        .wpm_bmc_input_content ::placeholder {
            color: #ccc;
            font-size: 12px;
        }
        .wpm_submit_button {
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 12px;
            min-height: 38px;
            cursor: pointer;
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
            <div style="margin-bottom:23px;">
                <h4 class="bmc_appreciation_title">Thanks for your appreciation.</h4>
            </div>
            <?php
                $form =  \buyMeCoffee\Builder\Render::renderInputElements();
                echo $form;
            ?>
        </div>
    </div>
</div>
<?php
wp_footer();
?>

<script type="text/javascript">

</script>

</body>
</html>
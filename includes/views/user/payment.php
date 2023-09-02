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
          width: 90%;
          display: flex;
          flex-direction: row-reverse;
          justify-content: space-between;
          max-width: 900px;
          margin: 0 auto;
        }
        #bmc_modal_wrapper::-webkit-scrollbar {
          display: none;
        }
          @media (max-width: 960px) {
                .buymecoffee_preview_body {
                      flex-direction: column;
                }
          }

        .bmc_appreciation_title {
            font-weight: 100;
            margin: 0;
        }
        h3.buymecoffee_form_to {
          margin: 10px 0px;
          font-size: 30px;
          color: #6d6d6d;
        }

        span.buymecoffee_form_to {
          color: #c0c0c0;
        }

        .buymecoffee_your_content_wrapper {
          display: flex;
          align-content: stretch;
          align-items: center;
        }

        .buymecoffee_form_preview_wrapper {
            max-width: 400px;
            background: white;
            width: 70%;
            border-left: 1px solid #f4f4f4;
            padding: 0px 36px;
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
            width: 98% !important;
            padding: 4px;
            border: 1px solid #e9e9e9;
            border-radius: 4px;
            resize: vertical;
            box-shadow: none;
            background: white !important;
        }
        .wpm_bmc_input_content input{
            height: 30px;
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
            background: #ffc568;
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
        .buymecoffee_profile_section_image {
          display: flex;
        }
        .buymecoffee_profile_section_image img {
            border-radius: 50%;
            width: 130px;
            height: 130px;
            border: 4px solid #dadada;
            margin: 0 auto;
            z-index: 999;
            background: #fff;
            margin-top: -65px;
        }
        .buymecoffee_profile_hr {
            height: 80px;
            width: 100%;
            background: #fff7eb;
        }
        .wpm_buymecoffee_no_signup{
            color: #b8b8b8;
            font-size: 12px;
            font-family: sans-serif;
            text-align: center;
        }
    </style>
<div id="buymecoffee_preview_top">
    <div class="buymecoffee_profile_section">
        <div class="buymecoffee_profile_hr">
        </div>
        <div class="buymecoffee_profile_section_image">
            <img src="<?php echo BUYMECOFFEE_URL . 'assets/images/coffee.png';?>"
                 alt="Profile Image">
        </div>

    </div>
    <div class="buymecoffee_preview_header">
        <h3><?php esc_html_e('', 'buymecoffee') ?></h3>
    </div>
    <div class="buymecoffee_preview_body">
        <div class="buymecoffee_form_preview_wrapper">
            <h3 class="buymecoffee_form_to">
                Buy
                <span class="buymecoffee_form_to"><?php esc_html_e($template['yourName'], 'buymecoffee') ?></span> <br/>
                a coffee
            </h3>
            <?php
                $form =  \buyMeCoffee\Builder\Render::renderInputElements();
                echo $form;
            ?>
        </div>
        <div class="buymecoffee_your_content_wrapper">
            <div class="buymecoffee_your_content">
                <div class="buymecoffee_your_content_title">
                    <div style="margin-bottom:23px;">
                        <h4 class="bmc_appreciation_title">Thanks for your appreciation.</h4>
                    </div>
                </div>
                <div class="buymecoffee_your_content_body">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
wp_footer();
?>
</body>
</html>
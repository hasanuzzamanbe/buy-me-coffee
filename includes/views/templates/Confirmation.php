<style>
    //confirmation
    .wpm_bmc_confirmation{
        min-width: 300px;
        box-shadow: #959da533 0 8px 24px;
        border-left: 5px solid #00b574;
        margin: 0!important;
        align-items: center;
    }
    .wpm_bmc_confirmation p {
        margin: 0;
    }
    .buymecoffee_preview_body {
        max-width: 500px;
        flex-direction: column;
    }
    .receipt {
        margin: 0 auto;
        border: 1px solid #ccc;
        padding: 50px 20px;
        min-height: 400px;
        min-width: 330px;
        display: flex;
        flex-direction: column;
        align-content: space-around;
        justify-content: center;
        box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
        border-radius: 10px;
    }
    span.wpm_bmc_status_paid {
        background: #e3fff0;
        padding: 0px 12px;
    }
    span.wpm_bmc_status_pending {
        background: #ffefe3;
        padding: 0px 12px;
    }

    .header h2{
        font-size:23px;
        text-align: center;
        color: #6d6d6d;
    }
    .header img {
        margin: 0 auto;
        display: block;
    }
    .content {
        margin-top: 10px;
    }
    .wpm_bmc_receipt_row {
        font-family: monospace;
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .wpm_bmc_receipt_row strong {
        font-weight: bold;
    }
    .wpm_bmc_confirmation_thanks {
        position: relative;
        bottom: -62px;
        text-align: center;
        font-size: 12px;
    }
    .wpm_bmc_receipt_coffee {
        height: 30px;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8e8e8;
        border-radius: 50%;
    }
</style>

<?php use BuyMeCoffee\Classes\Vite;

if ($paymentData): ?> <div class="wpm_bmc_confirmation">
    <div class='receipt'>
        <div class='header'>
            <img width="100" src="<?php echo esc_url(Vite::staticPath() . 'images/coffee.png'); ?>">
            <h2>Payment Receipt</h2>
            <div style="text-align: center;font-size: 12px;margin: 8px;font-family: monospace;">
                <strong>
                    #<?php echo esc_html($paymentData->entry_hash??''); ?>
                </strong>
            </div>
        </div>
        <hr/>
        <div class='content'>
            <div class='wpm_bmc_receipt_row'>
                <strong>Coffee Donated:</strong>
                <p class="wpm_bmc_receipt_coffee"><?php echo esc_html($paymentData->coffee_count??''); ?></p>
            </div>
            <div class='wpm_bmc_receipt_row'>
                <strong>Pay Status:</strong>
               <span class="<?php echo 'wpm_bmc_status_' . esc_html($paymentData->payment_status??''); ?>"><?php echo esc_html($paymentData->payment_status??''); ?></span>
            </div>
            <div class='wpm_bmc_receipt_row'>
                <strong>Payment Method:</strong>
               <?php echo esc_html($paymentData->payment_method??''); ?>
            </div>
            <div class='wpm_bmc_receipt_row'>
                <strong>Amount Paid:</strong>
               <?php echo esc_html($paymentData->currency??'') .' '. esc_html(floatval($paymentData->payment_total??0 / 100)); ?>
            </div>
            <div class='wpm_bmc_receipt_row'>
                <strong>Date:</strong>
                <?php echo esc_html($paymentData->created_at??''); ?>
            </div>
        </div>
        <p class="wpm_bmc_confirmation_thanks">Thanks for your contribution 🖤</p><br/>
    </div>
</div>
<?php else: ?>
    <div class="wpm_bmc_confirmation">
        <p>Thanks for your contribution 🖤</p><br/>
    </div>
<?php endif; ?>


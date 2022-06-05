<?php
namespace buyMeCoffee\Models;

use buyMeCoffee\Helpers\PaymentHelper;

class Supporters
{
    public function index($args)
    {

        $offset = intval( $args['page'] * $args['posts_per_page']);

       $query =  wpmBmcDB()->table('wpm_bmc_supporters')
       ->orderBy('ID', 'DESC')
       ->offset($offset)
       ->limit($args['posts_per_page']);

        $total = $query->count();

        $supporters = $query->get();

        $lastPage = ceil($total / $args['posts_per_page']);

        foreach ($supporters as $supporter) {
            $supporter->amount_formatted = PaymentHelper::getFormattedAmount($supporter->payment_total, $supporter->currency);
        }
        PaymentHelper::currencySymbol($lastPage);

        wp_send_json_success(
            array(
                'supporters' => $supporters,
                'total'     => $total,
                'last_page' => $lastPage
            ),
            200
        );
    }

    public function find()
    {
        return array(
            'hello there'
        );
    }
}

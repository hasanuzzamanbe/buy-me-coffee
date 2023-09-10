<?php
namespace BuyMeCoffee\Models;

use BuyMeCoffee\Helpers\PaymentHelper;

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

       $count = wpmBmcDB()->table('wpm_bmc_supporters')
                ->select(wpmBmcDB()->raw('SUM(coffee_count) as total_coffee'))
               ->first();

        $currencyTotal = wpmBmcDB()->table('wpm_bmc_supporters')
            ->groupBy('currency')
            ->where('payment_status', 'paid')
            ->select(wpmBmcDB()->raw('SUM(payment_total) as total_amount, currency'))
            ->get();

        wp_send_json_success(
            array(
                'supporters' => $supporters,
                'total'     => $total,
                'last_page' => $lastPage,
                'reports' => array(
                    'total_supporters' => $total ,
                    'total_coffee' =>  $count->total_coffee,
                    'currency_total' => $currencyTotal,
//                    'total_received' =>
                )
            ),
            200
        );
    }

    public function updateData($entryId, $data)
    {
        $supporters = wpmBmcDB()->table('wpm_bmc_supporters')->where('id', $entryId)->update($data);
        return $supporters;
    }

    public function find($id)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_supporters')
        ->where('id', $id)->first();

        return $supporter;
    }

    public function updateStatus()
    {

    }
    public function delete($id)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_supporters')->where('id', $id)->delete();

        return $supporter;
    }

}

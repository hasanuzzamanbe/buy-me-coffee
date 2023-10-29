<?php

namespace BuyMeCoffee\Models;

use BuyMeCoffee\Helpers\PaymentHelper;

class Supporters
{
    public function index($args)
    {
        $offset = intval($args['page'] * $args['posts_per_page']);

        $query = wpmBmcDB()->table('wpm_bmc_supporters')
            ->offset($offset)
            ->limit($args['posts_per_page']);

        $total = $query->count();
        $lastPage = ceil($total / $args['posts_per_page']);

        // if top supporter filters add
        if (isset($args['filter_top'])) {
            $query->where('payment_status', 'paid')
                ->orderBy('payment_total', 'DESC');

            $currencyTotalPending = wpmBmcDB()->table('wpm_bmc_supporters')
                ->groupBy('currency')
                ->where('payment_status', 'pending')
                ->select(wpmBmcDB()->raw('SUM(payment_total) as total_amount, currency'))
                ->get();

            foreach ($currencyTotalPending as $currency) {
                $currency->formatted_total = PaymentHelper::getFormattedAmount($currency->total_amount, $currency->currency);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }
        $supporters = $query->get();

        foreach ($supporters as $supporter) {
            $supporter->amount_formatted = PaymentHelper::getFormattedAmount($supporter->payment_total, $supporter->currency);
        }

        $count = wpmBmcDB()->table('wpm_bmc_supporters')
            ->select(wpmBmcDB()->raw('SUM(coffee_count) as total_coffee'))
            ->first();

        $currencyTotal = wpmBmcDB()->table('wpm_bmc_supporters')
            ->groupBy('currency')
            ->where('payment_status', 'paid')
            ->orWhere('payment_status', 'paid-initially')
            ->select(wpmBmcDB()->raw('SUM(payment_total) as total_amount, currency'))
            ->get();

        foreach ($currencyTotal as $currency) {
            $currency->formatted_total = PaymentHelper::getFormattedAmount($currency->total_amount, $currency->currency);
        }

        wp_send_json_success(
            array(
                'supporters' => $supporters,
                'total' => $total,
                'last_page' => $lastPage,
                'reports' => array(
                    'total_supporters' => $total,
                    'total_coffee' => $count->total_coffee,
                    'currency_total' => $currencyTotal,
                    'currency_total_pending' => $currencyTotalPending??[],
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
            ->where('wpm_bmc_supporters.id', $id)
            ->first();

        $transaction = wpmBmcDB()->table('wpm_bmc_transactions')
            ->where('entry_id', $id)
            ->first();

        if ($supporter) {
            $supporter->transaction = $transaction;
        }
         return $supporter;
    }

    public static function getByHash($hash)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_supporters')
            ->where('entry_hash', $hash)
            ->first();

        if ($supporter) {
            $transaction = wpmBmcDB()->table('wpm_bmc_transactions')
                ->where('entry_id', $supporter->id)
                ->where('entry_hash', $hash)
                ->first();
            $supporter->transaction = $transaction;
        }
        return $supporter;
    }

    public function getWeeklyRevenue()
    {
        $revenue = wpmBmcDB()->table('wpm_bmc_supporters')->select(
            'currency',
            'payment_status',
            wpmBmcDB()->raw('Date(created_at) as date'),
            wpmBmcDB()->raw("SUM(round(payment_total / 100, 2)) as total_paid"),
            wpmBmcDB()->raw("COUNT(*) as submissions")
        )->whereIn('payment_status', ['paid'])
            ->where('payment_total', '>', 0)
            ->groupBy([wpmBmcDB()->raw('Date(created_at)'), 'currency'])
            ->orderBy('id', 'desc')
            ->limit(50)
            ->getArray();

        $group = array();
        foreach ( $revenue as $value ) {
            $group[$value['currency']][] = $value;
        }

        $groupSelect = array();
        $chartData = array();
        $valueLength = 0;
        $topPaidCurrency = '';
        foreach ($group as $key => $value) {
            if ($valueLength < count($value)) {
                $valueLength = count($value);
                $topPaidCurrency = $key;
            }

            $groupSelect[] = array(
                'label' => $key,
                'value' => $key,
            );
            foreach ($value as $val) {
                $chartData[$key]['label'][] = $val['date'];
                $chartData[$key]['data'][] = floatval($val['total_paid']);
            }
        }

        wp_send_json_success([
            'data' => $group,
            'options' => $groupSelect,
            'chartData' => $chartData,
            'topPaidCurrency' => $topPaidCurrency,
        ], 200);
    }

    public function delete($id)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_supporters')->where('id', $id)->delete();

        return $supporter;
    }

}

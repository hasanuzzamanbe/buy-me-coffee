<?php

namespace BuyMeCoffee\Models;

class Transactions
{
    public function updateData($id, $data)
    {
        $supporters = wpmBmcDB()->table('wpm_bmc_transactions')->where('id', $id)->update($data);
        return $supporters;
    }

    public function find($id)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_transactions');
        if ($id) {
            $supporter = $supporter->where('id', $id);
        }
        return $supporter->first();
    }

    public function getByPaymentId($chargeId)
    {
        $payment = wpmBmcDB()->table('wpm_bmc_transactions')
            ->where('charge_id', $chargeId)
            ->where('payment_method', 'paypal')
            ->first();
        if ($payment) {
            return $payment->id;
        }
        return false;
    }
}

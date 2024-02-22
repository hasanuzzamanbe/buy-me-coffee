<?php

namespace BuyMeCoffee\Models;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Transactions
{
    public function updateData($id, $data)
    {
        $supporters = buyMeCoffeeQuery()->table('buymecoffee_transactions')->where('id', $id)->update($data);
        return $supporters;
    }

    public function find($id, $column='id')
    {
        $supporter = buyMeCoffeeQuery()->table('buymecoffee_transactions');
        if ($id) {
            $supporter = $supporter->where($column, $id);
        }
        return $supporter->first();
    }

    public function delete($id, $column='id')
    {
        return buyMeCoffeeQuery()->table('buymecoffee_transactions')->where('entry_id', $id)->delete();
    }

    public function getByPaymentId($chargeId, $method = 'paypal')
    {
        $payment = buyMeCoffeeQuery()->table('buymecoffee_transactions')
            ->where('charge_id', $chargeId)
            ->where('payment_method', $method)
            ->first();

        if ($payment) {
            return $payment->id;
        }
        return false;
    }
}

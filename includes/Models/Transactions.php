<?php
namespace buyMeCoffee\Models;

class Transactions
{
    public function updateData($id, $data)
    {
        $supporters = wpmBmcDB()->table('wpm_bmc_transactions')->where('id', $id)->update($data);
        return $supporters;
    }

    public function find($id)
    {
        $supporter = wpmBmcDB()->table('wpm_bmc_transactions')
        ->first();

        return $supporter;
    }
}

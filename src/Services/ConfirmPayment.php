<?php

namespace Vibraniuum\Pamtechoga\Services;

use Vibraniuum\Pamtechoga\Models\CreditLog;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\OrderDebt;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Models\PaymentSplit;

class ConfirmPayment
{
    // algorithm
    // check if organization's split payments total is less than organization bf_amount
    //     if true
    //         if payment amount > (bf_amount - split payments total) AKA bf_balance
    //             payment amount = payment amount - bf_balance
    //             create split record with bf_balance
    //         else
    //             create split record with payment amount
    //             payment amount = 0

    // orders = get all incomplete orders
    // if payment amount <= 0 : return
    // for each order
    // if order debt balance <= payment
    //      create split record
    //      payment -= order debt balance
    //      mark order as complete
    //      set order debt balance to 0
    // else
    //     create split record
    //     order debt balance -= payment amount
    //     payment amount = 0
    //     exit loop
    // end for
    // if payment amount > 0
    //     update organization credit balance
    //     log credit balance

    public function run(Payment $payment)
    {
        $paymentAmount = $payment->amount;
        $organizationId = $payment->organization_id;

        $splitsTotal = PaymentSplit::where('bf_organization_id', $organizationId)->sum('amount');
        $organization = Organization::where('id', $organizationId)->first();
        $bfBalance = $organization->bf_amount - $splitsTotal;

        if($bfBalance > 0) {
            if($paymentAmount > $bfBalance) {
                $paymentAmount = $paymentAmount - $bfBalance;
                PaymentSplit::create([
                    'payment_id' => $payment->id,
                    'bf_organization_id' => $organizationId,
                    'amount' => $bfBalance,
                ]);
            } else {
                PaymentSplit::create([
                    'payment_id' => $payment->id,
                    'bf_organization_id' => $organizationId,
                    'amount' => $paymentAmount,
                ]);
                $paymentAmount = 0;
            }
        }

        // get only incomplete orders
        $orders = Order::where('organization_id', $organizationId)
            ->where('payment_is_complete', false)
//            ->where('status', '<>', 'PENDING')
            ->where('status', '<>', 'CENCELED')
            ->get();

        if($paymentAmount <= 0) {
            return;
        }

        foreach ($orders as $order) {
            if($order->orderDebt->balance <= $paymentAmount) {
                PaymentSplit::create([
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'amount' => $order->orderDebt->balance,
                ]);

                // update payment amount
                $paymentAmount = $paymentAmount - $order->orderDebt->balance;

                $order->payment_is_complete = true;
                $order->save();

                $orderDebt = OrderDebt::where('order_id', $order->id)->first();
                $orderDebt->balance = 0;
                $orderDebt->save();
            } else {
                $paymentAmount = $order->orderDebt->balance - $paymentAmount;

                PaymentSplit::create([
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'amount' => $paymentAmount,
                ]);

                $orderDebt = OrderDebt::where('order_id', $order->id)->first();
                $orderDebt->balance = $paymentAmount;
                $orderDebt->save();

                $paymentAmount = 0;

                break;
            }
        }

        if ($paymentAmount > 0) {
            // log credit balance
            CreditLog::create([
                'organization_id' => $organizationId,
                'payment_id' => $payment->id,
//                'amount' => $payment->amount,
                'amount' => $paymentAmount,
                'before_balance' => $organization->credit,
            ]);

            // update organization credit balance
            $organization->credit = $organization->credit + $paymentAmount;
            $organization->save();
        }
    }

    public function payFromCredit(Organization $organization)
    {
        $amount = OrderDebt::where('organization_id', $organization->id)->sum('balance');

        if($amount <= 0) {
            return;
        }

        if($amount > $organization->credit) {
            $payment = Payment::create([
                'organization_id' => $organization->id,
                'amount' => $organization->credit,
                'status' => 'CONFIRMED',
                'type' => 'CREDIT',
                'payment_date' => now(),
            ]);

            $organization->credit = 0;
            $organization->save();

            $this->run($payment);
        } else {
            $organization->credit = max($organization->credit - $amount, 0);
            $organization->save();

            $payment = Payment::create([
                'organization_id' => $organization->id,
                'amount' => $amount,
                'status' => 'CONFIRMED',
                'type' => 'CREDIT',
                'payment_date' => now(),
            ]);

            $this->run($payment);
        }
    }

    /*
     * Refund a payment algorithm
     * for a given payment
     * set payment status to refunded
     * get all splits
     *
     * for each split with the given payment id
     *    set split status to refunded
     *    set order debt balance to split amount + order debt balance
     *
     *    if order debt balance > 0
     *      mark order as incomplete
     *    else
     *     mark order as complete
     * end for
     */
    public function refund(Payment $payment) {
        $payment->status = 'REFUNDED';
        $payment->save();

        $splits = PaymentSplit::where('payment_id', $payment->id)->where('status', '<>', 'REFUNDED')->get();
        $splitsAmount = PaymentSplit::where('payment_id', $payment->id)->where('status', '<>', 'REFUNDED')->sum('amount');
        foreach ($splits as $split) {
            $split->status = 'REFUNDED';
            $split->save();

            $orderDebt = OrderDebt::where('order_id', $split->order_id)->first();
            $orderDebt->balance = $split->amount + $orderDebt->balance;
            $orderDebt->save();

            if($orderDebt->balance > 0) {
                $order = Order::where('id', $split->order_id)->first();
                $order->payment_is_complete = false;
                $order->save();
            } else {
                $order = Order::where('id', $split->order_id)->first();
                $order->payment_is_complete = true;
                $order->save();
            }
        }

        if($payment->amount > $splitsAmount) {
            $remainder = max($payment->amount - $splitsAmount, 0);
            // get most recent order debt for this organization
            $orderDbt = OrderDebt::where('organization_id', $payment->organization_id)->orderBy('id', 'desc')->first();

            if(is_null($orderDbt)) {
                return;
            }

            $orderDbt->balance = $orderDbt->balance + $remainder;
            $orderDbt->save();
        }
    }


}

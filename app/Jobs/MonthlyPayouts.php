<?php

namespace App\Jobs;

use App\Http\Controllers\PayoutsController;
use App\PayoutCronHistory;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PayPal\Api\Currency;
use PayPal\Api\Payout as PaypalPayout;
use PayPal\Api\PayoutItem as PaypalPayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;

class MonthlyPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$last_payout_history = PayoutCronHistory::latest()->first();

    	$first_monday_date = Carbon::now()->firstOfMonth(1);

    	if (now()->isMonday() && now()->day === $first_monday_date->day) {
    		if (!$last_payout_history || $last_payout_history->created_at->day !== $first_monday_date->day && $last_payout_history->created_at->month !== $first_monday_date->month) {
			    $advisors = User::where([
				    'role_id' => Role::getAdvisorId(),
				    'approved' => 1,
				    'blocked' => 0,
				    'deleted' => 0,
				    'payouts_enabled' => 1
			    ])
			                    ->where('balance', '>', 75)
			                    ->limit(1000)
			                    ->get();

			    if ($advisors) {
				    $paypal_payout = new PaypalPayout();

				    $sender_batch_header = new PayoutSenderBatchHeader();
				    $sender_batch_header->setSenderBatchId(uniqid());
				    $sender_batch_header->setEmailSubject('Payment from gopsys.com');
				    $paypal_payout->setSenderBatchHeader($sender_batch_header);

				    foreach ($advisors as $user) {
					    $fee_amount = ($user->balance / 100) * 2;
				    	$payout_amount = $user->balance - $fee_amount;

					    $currency = new Currency();
					    $currency->setValue($payout_amount);
					    $currency->setCurrency('USD');

					    $payout_item = new PaypalPayoutItem();
					    $payout_item->setRecipientType('EMAIL');
					    $payout_item->setAmount($currency);
					    $payout_item->setNote('Payment to '. $user->email .' from gopsys.com');
					    $payout_item->setSenderItemId(uniqid());
					    $payout_item->setReceiver($user->payment_email);

					    $paypal_payout->addItem($payout_item);
				    }

				    $payout = PayoutsController::createPayout($paypal_payout);

				    $payout->cronHistory()->create();
			    }
		    }
	    }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}

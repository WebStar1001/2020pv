<?php
// SHURA improve with some php artisan generate:consts
return [
    'roles' => [
        'super_admin_id' => 1,
        'admin_id' => 2,
        'customer_id' => 3,
        'advisor_id' => 4,
    ],
	'payments' => [
		'fund_added' => 'Fund Added',
		'chat_outcome' => 'Chat session charge',
		'chat_income' => 'Chat amount Received',
		'chat_inactivity_money_back' => 'Money back due to inactive chat',
		'chat_inactivity_charge' => 'Charge due to inactive chat',
		'monthly_withdrawal' => 'Monthly Withdrawal',
		'unclaimed_payout' => 'Payout moneyback (Nonexistent PayPal account)',
		'email_chat_outcome' => 'Invoice Paid',
		'email_chat_income' => 'Invoice Payment'
	],
	'chat_pauses' => [
		'adding_funds' => 'Adding Funds',
		'insufficient_funds' => 'Insufficient funds',
		'advisor_is_inactive' => 'Advisor is inactive'
	]
];

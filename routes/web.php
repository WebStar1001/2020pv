<?php

Route::model('user', App\User::class);
Route::model('user2', App\User::class);
Route::model('chatSession', App\ChatSession::class);
Route::bind('service', function ($value) {
	return App\Service::where('slug', $value)->first() ?? abort(404);
});
Route::model('payoutItem', App\PayoutItem::class);
Route::model('dispute', App\Dispute::class);
Route::model('subject', App\EmailSubject::class);
Route::model('invoice', App\EmailMessage::class);
Route::model('discount', App\Discount::class);
Route::bind('slug', function ($value) {
	return App\User::where('slug', $value)->first() ?? abort(404);
});

// Authentication Routes...
Route::get('register', 'PublicController@index')->name('register')->middleware('guest');
Route::post('register', 'Auth\RegisterController@register');
Route::get('psychic-register', 'PublicController@index')->name('register')->middleware('guest');
Route::post('psychic-register', 'Auth\RegisterController@registerAdvisor');
Route::get('login', 'PublicController@index')->name('login')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('auth.login')->middleware('guest');
Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');
Route::get('set-password/{token}', 'PublicController@index')->name('set.password');

// paypal callback
Route::get('payment/callback', 'PaymentController@callback')->name('payment.callback');

// paypal webhooks
Route::post('webhooks/payouts', 'WebhooksController@payouts')->name('webhooks.payouts');
Route::post('webhooks/disputes', 'WebhooksController@disputes')->name('webhooks.disputes');

// public routes
Route::group([], function () {
	Route::post('reset-password', 'Auth\ResetPasswordController@resetPasswordWithoutToken');
	Route::post('set-password', 'Auth\ResetPasswordController@reset');

	Route::get('countries', 'UsersController@countries');
	Route::get('category/{service}/advisors', 'AdvisorsController@getCategoryAdvisors');
	Route::get('all-advisors', 'AdvisorsController@getAdvisors');
	Route::get('get-profile/{slug}', 'AdvisorsController@getProfile');
	Route::get('user/{slug}', 'UsersController@getUser');
	Route::get('featured-advisors', 'AdvisorsController@getFeaturedAdvisors');
	Route::get('unsubscribe/{hash}', 'NewsletterController@unsubscribe')->name('newsletter.unsubscribe');
});

// admin routes
Route::group(['middleware' => ['auth']], function () {
	Route::get('dashboard', 'DashboardController@getData');

	Route::apiResource('users', 'UsersController')->middleware('can:index,App\User,user');
	Route::post('users/{user}/change-password', 'Auth\ChangePasswordController@adminChangePassword')->middleware('can:change_password,App\User,user');
	Route::post('users/{user}/reset-password', 'Auth\ChangePasswordController@sendResetPasswordLink')->middleware('can:reset_password,App\User,user');
	Route::post('users/{user}/approve', 'UsersController@approveAccount')->middleware('can:approve_account,App\User,user');
	Route::post('users/{user}/decline', 'UsersController@declineAccount')->middleware('can:decline_account,App\User,user');
	Route::post('users/{user}/change-balance', 'UsersController@changeBalance')->middleware('can:update,App\User,user');
	Route::post('users/{user}/favorite', 'UsersController@addOrRemoveFavoriteAdvisor')->middleware('can:update_favorites,App\User,user');
	Route::post('users/{user}/login-as-user', 'UsersController@loginAsUser')->middleware('can:login_as_user,App\User,user');
	Route::post('users/{user}/login-as-superadmin', 'UsersController@loginAsSuperadmin')->middleware('can:login_as_superadmin,App\User,user');
	Route::get('users/{user}/chat-history', 'ChatsController@readingHistory')->middleware('can:view_reading_history,App\ChatSession,user');
	Route::post('users/{user}/block', 'AdvisorsController@blockCustomer')->middleware('can:block_customers,App\User,user');
	Route::post('users/{user}/unblock', 'AdvisorsController@unblockCustomer')->middleware('can:block_customers,App\User,user');
	Route::post('user/status', 'UsersController@changeStatus')->middleware('can:update_status,App\User');
	Route::post('user/payouts-enabled', 'UsersController@changePayoutsEnabled')->middleware('can:update_payouts,App\User');
	Route::post('user/activity', 'UsersController@updateActivity')->middleware('can:update_activity,App\User');

	Route::get('users/{user}/reviews', 'AdvisorsController@getReviews')->middleware('can:view_reviews,App\User,review');
	Route::get('reviews/{review}', 'AdvisorsController@getReview')->middleware('can:edit_reviews,App\User,review');
	Route::post('reviews/{review}', 'AdvisorsController@updateReview')->middleware('can:edit_reviews,App\User,review');
	Route::delete('reviews/{review}', 'AdvisorsController@destroyReview')->middleware('can:delete_reviews,App\User,review');

	Route::get('users/{user}/free-minutes', 'UsersController@getFreeMinutes')->middleware('can:get_free_minutes,App\User,user');
	Route::get('users/{user}/free-minutes/{user2}', 'UsersController@getFreeMinutesForAdmin')->middleware('can:get_free_minutes,App\User,user');
	Route::post('users/{user}/free-minutes/{user2}', 'UsersController@updateFreeMinutes')->middleware('can:add_free_minutes,App\User,user');
	Route::get('get-advisors', 'UsersController@getAdvisors')->middleware('can:add_free_minutes,App\User');
	Route::get('get-customers', 'UsersController@getCustomers')->middleware('can:add_free_minutes,App\User');

	Route::get('pending-payment-emails', 'PendingPaymentEmailsController@getPendingPaymentEmails')->middleware('can:view_pending_payment_emails,App\Payment');
	Route::post('pending-payment-emails/{user}', 'PendingPaymentEmailsController@approvePaymentEmail')->middleware('can:update_payment_emails,App\Payment');

	Route::get('chat/reading-history', 'ChatsController@readingHistory')->middleware('can:view_reading_history,App\ChatSession');
	Route::delete('chat/reading-history/{chatSession}', 'ChatsController@deleteReadingHistory')->middleware('can:delete_reading_history,App\ChatSession');
	Route::post('chat/{user}/start', 'ChatsController@startChat')->middleware('can:start_chat,App\ChatSession,user');
	Route::post('chat/accept', 'ChatsController@acceptCall')->middleware('can:accept_call,App\ChatSession');
	Route::post('chat/cancel', 'ChatsController@cancelCall')->middleware('can:cancel_call,App\ChatSession');
	Route::post('chat/busy', 'ChatsController@advisorIsBusy');
	Route::get('chat/{chatSession}/data', 'ChatsController@getData')->middleware('can:view,App\ChatSession,chatSession');
	Route::post('chat/{chatSession}/messages', 'ChatsController@sendMessage')->middleware('can:send_messages,App\ChatSession');
	Route::post('chat/{chatSession}/end', 'ChatsController@endChat')->middleware('can:end_chat,App\ChatSession');
	Route::post('chat/{chatSession}/upload-file', 'ChatsController@uploadFile')->middleware('can:upload_files,App\ChatSession');
	Route::post('chat/{chatSession}/remove-file', 'ChatsController@removeFile')->middleware('can:upload_files,App\ChatSession');
	Route::post('chat/{chatSession}/review', 'ChatsController@storeReview')->middleware('can:create_reviews,App\ChatSession');
	Route::post('chat/{chatSession}/report', 'ChatsController@storeReport')->middleware('can:create_reports,App\ChatSession');
	Route::post('chat/{chatSession}/out-of-money', 'ChatsController@outOfMoney')->middleware('can:send_out_of_money,App\ChatSession');
	Route::post('chat/{chatSession}/advisor-is-inactive', 'ChatsController@advisorIsInactive')->middleware('can:send_advisor_is_inactive,App\ChatSession');
	Route::get('chat/{chatSession}/messages/pdf', 'ChatsController@downloadHistory')->middleware('can:download_history,App\ChatSession');

	Route::get('payments', 'PaymentController@index')->middleware('can:index,App\Payment');
	Route::get('payments/{paymentId}/invoice', 'PaymentController@getInvoice')->middleware('can:index,App\Payment');
	Route::post('payment', 'PaymentController@payment')->middleware('can:pay,App\Payment');;

	Route::get('payouts', 'PayoutsController@getPayouts')->middleware('can:view_own_payouts,App\Payout');
	Route::get('payouts/advisors', 'PayoutsController@getAdvisors')->middleware('can:view_all_payouts,App\Payout');
	Route::post('payouts/advisors', 'PayoutsController@makePayouts')->middleware('can:make_payouts,App\Payout');
	Route::get('payouts/payout-item/{payoutItem}', 'PayoutsController@getPayoutItemDetails')->middleware('can:view_all_payouts,App\Payout');
	Route::post('payouts/payout-item/{payoutItem}/cancel', 'PayoutsController@cancelUnclaimedPayout')->middleware('can:make_payouts,App\Payout');
	Route::post('payouts/payout-item/{payoutItem}/retry', 'PayoutsController@retryFailedPayout')->middleware('can:make_payouts,App\Payout');

	Route::get('disputes', 'DisputesController@getDisputes')->middleware('can:index,App\Dispute');
	Route::get('disputes/{dispute}', 'DisputesController@getDisputeDetails')->middleware('can:view,App\Dispute');

	Route::get('reports', 'ChatsController@getReports')->middleware('can:view_reports,App\ChatSession');

	Route::get('my-clients', 'UsersController@getMyClients')->middleware('can:view_my_clients,App\User');
	Route::get('my-psychics', 'UsersController@getMyPsychics')->middleware('can:view_my_psychics,App\User');

	Route::get('settings/general', 'SettingsController@getGeneralInformation');
	Route::post('settings/general', 'SettingsController@updateGeneralInformation');
	Route::post('settings/security', 'SettingsController@updateSecurity');
	Route::post('settings/security/delete-account', 'SettingsController@deleteAccount');
	Route::post('settings/notifications', 'SettingsController@updateNotifications');
	Route::get('settings/payment', 'SettingsController@getPaymentData')->middleware('can:view_payment_settings,App\Payment');
	Route::post('settings/payment', 'SettingsController@updatePaymentData')->middleware('can:update_payment_settings,App\Payment');

	Route::get('transactions', 'PaymentController@getTransactions')->middleware('can:view_transactions,App\Payment');

	Route::get('chat-sessions', 'ChatsController@getChatSessions')->middleware('can:view_chat_sessions,App\ChatSession');

	Route::post('newsletter', 'NewsletterController@enqueue')->middleware('can:send_newsletter,App\User');
	Route::post('newsletter/test', 'NewsletterController@test')->middleware('can:send_newsletter,App\User');

	Route::get('global-settings', 'GlobalSettingsController@getSettings')->middleware('can:view_global_settings,App\User');
	Route::post('global-settings', 'GlobalSettingsController@updateOrCreateSettings')->middleware('can:edit_global_settings,App\User');

	Route::get('discounts', 'DiscountsController@getDiscounts')->middleware('can:view_discounts,App\User');
	Route::post('discounts', 'DiscountsController@store')->middleware('can:create_discounts,App\User');
	Route::get('discounts/{discount}', 'DiscountsController@getDiscount')->middleware('can:view_discounts,App\User');
	Route::post('discounts/{discount}', 'DiscountsController@update')->middleware('can:edit_discounts,App\User');
	Route::delete('discounts/{discount}', 'DiscountsController@destroy')->middleware('can:delete_discounts,App\User');

	Route::get('email-subjects', 'MessagesController@getEmailSubjects')->middleware('can:view_all_email_subjects,App\EmailSubject');
	Route::get('subjects', 'MessagesController@getSubjects')->middleware('can:view_email_subjects,App\EmailSubject');
	Route::get('subjects/advisors', 'MessagesController@getSubjectsAdvisors')->middleware('can:create_email_subjects,App\EmailSubject');
	Route::post('messages/{slug}/new-subject', 'MessagesController@createNewSubject')->middleware('can:create_email_subjects,App\EmailSubject');
	Route::get('subjects/{subject}/messages', 'MessagesController@getSubjectMessages')->middleware('can:view_email_messages,App\EmailSubject');
	Route::post('subjects/{subject}/messages', 'MessagesController@sendMessage')->middleware('can:create_email_messages,App\EmailSubject');
	Route::post('subjects/{subject}/invoice', 'MessagesController@sendInvoice')->middleware('can:create_invoices,App\EmailSubject');
	Route::post('subjects/{subject}/invoice/{invoice}/cancel', 'MessagesController@cancelInvoice')->middleware('can:cancel_invoices,App\EmailSubject');
	Route::post('subjects/{subject}/invoice/{invoice}/reject', 'MessagesController@rejectInvoice')->middleware('can:reject_invoices,App\EmailSubject');
	Route::post('subjects/{subject}/invoice/{invoice}/accept', 'MessagesController@acceptInvoice')->middleware('can:accept_invoices,App\EmailSubject');
	Route::post('subjects/{subject}/upload-file', 'MessagesController@uploadFile')->middleware('can:upload_files,App\EmailSubject');
	Route::post('subjects/{subject}/remove-file', 'MessagesController@removeFile')->middleware('can:remove_files,App\EmailSubject');
	Route::post('subjects/{subject}/report', 'MessagesController@storeReport')->middleware('can:create_reports,App\EmailSubject');
	Route::post('subjects/{subject}/read', 'MessagesController@updateReadStatus')->middleware('can:view_email_messages,App\EmailSubject');
	Route::post('subjects/{subject}/review', 'MessagesController@storeReview')->middleware('can:create_reviews,App\EmailSubject');
	Route::get('subjects/{subject}/messages/pdf', 'MessagesController@downloadHistory')->middleware('can:download_history,App\EmailSubject');

	Route::get('/admin/{any}', 'HomeController@index')->where('any', '.*');
});

Route::get('/{any}', 'PublicController@index')->where('any', '.*');
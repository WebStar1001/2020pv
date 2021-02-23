<?php

namespace App\Providers;

use App\ChatSession;
use App\Dispute;
use App\EmailSubject;
use App\Payment;
use App\Payout;
use App\PendingPaymentEmail;
use App\Policies\ChatSessionPolicy;
use App\Policies\DisputePolicy;
use App\Policies\EmailSubjectPolicy;
use App\Policies\MenuPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PayoutPolicy;
use App\Policies\PendingPaymentEmailPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Payment::class => PaymentPolicy::class,
	    ChatSession::class => ChatSessionPolicy::class,
        PendingPaymentEmail::class => PendingPaymentEmailPolicy::class,
        Payout::class => PayoutPolicy::class,
        Dispute::class => DisputePolicy::class,
        EmailSubject::class => EmailSubjectPolicy::class,
        'App\Model' => MenuPolicy::class,
    ];

    // Use for define right on frontend
    private static $POLICIES_FRONTEND_MAPPER = [
        'user' => User::class,
    ];
    public static function getPolicies() {
        return self::$POLICIES_FRONTEND_MAPPER;
    }
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

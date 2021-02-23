<?php

namespace App\Providers;

use App\Role;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        // SHURA move it to some script for artisan
//        Role::$SUPERADMINROLEID = Role::where('slug', 'super_admin')->first()->id ?? -1;
//        Role::$COMPANYADMINROLEID = Role::where('slug', 'company_admin')->first()->id ?? -1;
//        Role::$COMPANYEMPLOYEEROLEID = Role::where('slug', 'company_employee')->first()->id ?? -1;
        //

    }
}

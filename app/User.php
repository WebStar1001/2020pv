<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
	    'email',
	    'payment_email',
	    'email_confirmed',
	    'password',
	    'api_token',
	    'remember_token',
	    'role_id',
	    'balance',
	    'display_name',
	    'slug',
	    'description',
	    'avatar',
	    'price_per_minute',
	    'commission_percentage',
	    'free_minutes_enabled',
	    'horoscope_id',
	    'master_service_id',
	    'experience',
	    'qualification',
	    'subscribed',
	    'approved',
	    'blocked',
	    'deleted',
	    'full_name',
	    'country',
	    'resume',
	    'decline_reason',
	    'ip',
	    'experience_years',
	    'highlight',
	    'about_services',
	    'last_activity',
	    'payouts_enabled',
	    'is_logged_in',
	    'rank',
	    'top_advisor',
	    'lead',
	    'paypal_payment',
	    'readings',
	    'rating',
	    'sales'
    ];

    protected $hidden = ['password', 'remember_token'];

	protected $dates = ['last_activity',];

	public function isSuperAdmin()
	{
		return $this->role_id === Role::getSuperAdminId();
    }

	public function isAdmin()
	{
		return $this->role_id === Role::getAdminId();
    }

	public function isAdvisor()
	{
		return $this->role_id === Role::getAdvisorId();
	}

	public function isCustomer()
	{
		return $this->role_id === Role::getCustomerId();
	}

	public function getAvatarAttribute($value)
	{
		if ($value) {
			return Storage::url( $value );
		}

		return '/images/no-avatar.png';
	}

    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setRoleIdAttribute($input)
    {
        $this->attributes['role_id'] = $input ? $input : null;
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

	public function horoscope()
	{
		return $this->belongsTo(Horoscope::class, 'horoscope_id');
	}

	public function masterService()
	{
		return $this->belongsTo(Service::class, 'master_service_id');
	}

    public function sendPasswordSetNotification($template = 'default')
    {
        $token = app('auth.password.broker')->createToken($this);

        $set_password_class = new class ($token, $this, $template) extends Notification {
            public $token, $to_user, $template;

            public function __construct($token, $to_user, $template)
            {
            	$this->token = $token;
            	$this->to_user = $to_user;
            	$this->template = $template;
            }

            public function via($notifiable)
            {
            	return ['mail'];
            }

            public function toMail($notifiable)
            {
            	switch ($this->template) {
		            case 'reset':
		            	return (new MailMessage)
				            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
				            ->subject("Reset password to " . env('APP_NAME'))
				            ->greeting('Hello!')
				            ->line('You are receiving this email because we received a password reset request for your account.')
				            ->action('Set New Password', url(route('set.password', $this->token, false)))
				            ->line('If you believe this email has been sent in error kindly disregard it.')
				            ->line('With regards')
				            ->line('GoPsys Team')
				            ->salutation(" ");
		            case 'account_approved':
			            return (new MailMessage)
				            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
				            ->subject('Your application is approved!')
				            ->greeting('Hello!')
				            ->line('Please click the "Set Password" button to create and activate your account.')
				            ->action('Set Password', url(route('set.password', $this->token, false)))
				            ->salutation(" ")
				            ->line('If you believe this email has been sent in error kindly disregard it.')
				            ->line('With regards')
				            ->line('GoPsys Team')
				            ->salutation(" ");
		            default:
			            return (new MailMessage)
				            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
				            ->subject("Welcome to " . env('APP_NAME'))
				            ->greeting('Hello!')
				            ->line('Please click the "Set Password" button to create and activate your account.')
				            ->action('Set Password', url(route('set.password', $this->token, false)))
				            ->salutation(" ")
				            ->line('If you believe this email has been sent in error kindly disregard it.')
				            ->line('With regards')
				            ->line('GoPsys Team')
				            ->salutation(" ");
	            }
            }
        };
        $this->notify($set_password_class);
    }

    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

	/**
	 * A user can have many messages
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages()
	{
		return $this->hasMany(Message::class);
	}

	/**
	 * A user can have many educations
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function educations()
	{
		return $this->hasMany(UserEducation::class);
	}

	/**
	 * A user can have many languages
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function languages()
	{
		return $this->hasMany(UserLanguage::class)->with('language');
	}

	/**
	 * A user can have many services
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function services()
	{
		return $this->hasMany(UserService::class)->with('service');
	}

	/**
	 * A user can have many requests for changing payment email
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function pendingPaymentEmails()
	{
		return $this->hasMany(PendingPaymentEmail::class);
	}

	public function emailSubjects()
	{
		if ($this->isCustomer()) {
			return $this->hasMany( EmailSubject::class, 'customer_id' );
		}

		if ($this->isAdvisor()) {
			return $this->hasMany( EmailSubject::class, 'advisor_id' );
		}

		return null;
	}

	public function emailMessages()
	{
		return $this->hasMany( EmailMessage::class, 'sender_id' );
	}

	public function status()
	{
		if (Cache::has('user-is-offline-' . $this->id)) {
			return 'offline';
		} elseif (Cache::has('user-is-busy-' . $this->id)) {
			return 'busy';
		} elseif ($this->is_logged_in) {
			return 'online';
		}

		return 'offline';
	}

	public function isOnline()
	{
		if (Cache::has('user-is-offline-' . $this->id)) {
			return false;
		}

		return $this->is_logged_in;
	}

	public function isChatting()
	{
		if ($this->isAdvisor() || $this->isCustomer()) {
			return $this->chatSessions()->where(['is_ended' => 0])->first() ? true : false;
		}

		return false;
	}

	public function activeChatSession()
	{
		if ($this->isAdvisor() || $this->isCustomer()) {
			return $this->chatSessions()->where( [ 'is_ended' => 0 ] )->first();
		}

		return null;
	}

	/**
	 * A user can have many paypal payments
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function paypalPayments()
	{
		return $this->hasMany(PaypalPayment::class);
	}

	/**
	 * A user can have many payments
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	/**
	 * A user can have many payments
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function freeMinutes()
	{
		return $this->hasMany(FreeMinute::class, 'customer_id');
	}

	/**
	 * A user can have many chat sessions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function chatSessions()
	{
		if ($this->isCustomer()) {
			return $this->hasMany(ChatSession::class, 'customer_id');
		}

		if ($this->isAdvisor()) {
			return $this->hasMany(ChatSession::class, 'advisor_id');
		}

		return null;
	}

	public function payoutItems()
	{
		return $this->hasMany(PayoutItem::class);
	}

	public function busyWithUserId()
	{
		return Cache::get('user-is-busy-' . $this->id);
	}

	public function reviews()
	{
		if ($this->isCustomer()) {
			return $this->hasMany(Review::class, 'customer_id');
		}

		if ($this->isAdvisor()) {
			return $this->hasMany(Review::class, 'advisor_id')->with('customer');
		}

		return null;
	}

	public function rating() {
		if ($this->isAdvisor()) {
			$rating = $this->reviews->avg('rating');

			return $rating ? $rating : 0;
		}

		return 0;
	}

	public function missedChats()
	{
		if ($this->isAdvisor()) {
			return $this->hasMany(MissedChat::class, 'advisor_id')->with('customer');
		}

		return null;
	}

	public function activeCall()
	{
		if ($this->isAdvisor() || $this->isCustomer()) {
			$active_call = $this->hasMany(MissedChat::class, $this->isAdvisor() ? 'advisor_id' : 'customer_id')
			            ->with('customer', 'advisor')
			            ->where('cancelled', 0)->where('created_at', '>', now()->subSeconds(180))->first();

			if ($active_call) {
				$active_call->seconds_remaining = 180 - now()->diffInSeconds($active_call->created_at);

				return $active_call;
			}
		}

		return null;
	}

	public function favoriteAdvisors()
	{
		if ($this->isCustomer()) {
			return $this->hasMany(FavoriteAdvisor::class, 'customer_id');
		}

		return null;
	}

	public function favoriteAdvisorsIds()
	{
		if ($this->isCustomer()) {
			return $this->hasMany(FavoriteAdvisor::class, 'customer_id')->pluck('advisor_id')->toArray();
		}

		return [];
	}

	public function blockedCustomers()
	{
		return $this->hasMany(BlockedCustomer::class, 'advisor_id');
	}

	public function blockedAdvisorsIds()
	{
		return $this->hasMany(BlockedCustomer::class, 'customer_id')->pluck('advisor_id')->toArray();
	}

	public function getUnreadMessagesCount()
	{
		return EmailMessage::where([
			'receiver_id' => $this->id,
			'read_status' => 0
		])->count();
	}

	public function discountHistory()
	{
		return $this->hasMany(DiscountsHistory::class, 'customer_id');
	}

	public function emailNotification()
	{
		return $this->hasOne(EmailNotification::class);
	}

	public function bankDetail()
	{
		return $this->hasOne(BankDetail::class);
	}

}

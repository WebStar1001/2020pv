<?php

namespace App;

use App\Events\BalanceUpdated;
use App\Events\CallEnded;
use App\Events\ChatStarted;
use App\Http\Controllers\PaymentController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatSession extends Model
{
	/**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'customer_id',
		'advisor_id',
		'price_per_minute',
		'commission_percentage',
		'discount',
		'duration',
		'free_chat_duration',
		'customer_balance_before',
		'customer_balance_after',
		'advisor_balance_before',
		'advisor_balance_after',
		'is_ended',
		'end_reason',
		'ended_at',
		'started_at',
		'deleted'
	];

	protected $dates = ['ended_at', 'started_at'];

	/**
	 * A chat session belong to a customer
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * A chat session belong to a advisor
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function advisor()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * A chat session has many messages
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages()
	{
		return $this->hasMany(Message::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class, 'relationship_id', 'id')->where(['user_id' => auth()->id()]);
	}

	public function review()
	{
		return $this->hasOne(Review::class, 'session_id', 'id');
	}

	public function reports()
	{
		return $this->hasMany(Report::class);
	}

	public function pauses()
	{
		return $this->hasMany(ChatPause::class);
	}

	public function isPaused()
	{
		$pause = $this->pauses()->whereNull('ended_at')->first();

		return $pause ? true : false;
	}

	public function pauseReason()
	{
		$pause = $this->pauses()->whereNull('ended_at')->first();

		return $pause ? $pause->reason : '';
	}

	public function isActive()
	{
		return !$this->ended_at && Cache::has('chat_session_id_' . $this->id) ? true : false;
	}

	public function moneybackAmount()
	{
		if ($this->end_reason === 'inactivity_money_back') {
			$payment = $this->payments()->where([
											'relationship_id' => $this->id,
											'user_id' => auth()->id(),

										])
			                            ->where(function($query) {
											return $query->where('type', constants('payments.chat_inactivity_money_back'))
											             ->orWhere('type', constants('payments.chat_inactivity_charge'));
			                            })
			                            ->first();

			if ($payment) {
				return $payment->amount;
			}
		}

		return 0;
	}

	/**
	 * End Chat Session
	 *
	 * @param string $reason (manually, customer_inactivity, advisor_inactivity, inactivity_money_back, cron)
	 */
	public function end($reason)
	{
		if (!$this->is_ended) {
			$ended_at = now();
			$free_chat_duration = 0;
			$paid_chat_duration = 0;
			$customer = $this->customer;
			$advisor  = $this->advisor;

			// if on pause end pause
			if ($this->isPaused()) {
				$this->pauses()
				     ->whereNull('ended_at')
				     ->where(['reason' => constants('chat_pauses.adding_funds')])
				     ->orWhere(['reason' => constants('chat_pauses.insufficient_funds')])
				     ->whereNull('ended_at')
				     ->update(['ended_at' => $ended_at]);
			}

			if ($this->started_at) {
				$duration = $ended_at->diffInSeconds($this->started_at);

				// subtract chat pauses
				foreach ($this->pauses as $pause) {
					$duration = $duration - $pause->created_at->diffInSeconds($pause->ended_at);
				}

				if ($advisor->free_minutes_enabled) {
					$free_minutes = $this->customer->freeMinutes()->where(['advisor_id' => $this->advisor_id])->first();

					if ($free_minutes) {
						$free_chat_duration = $free_minutes->free_seconds > $duration ? $free_minutes->free_seconds - ($free_minutes->free_seconds - $duration) : $free_minutes->free_seconds;

						$free_minutes->update(['free_seconds' => $free_minutes->free_seconds - $free_chat_duration]);
					}
				}

				$paid_chat_duration = $duration - $free_chat_duration;
			}

			$customer_outcome = ($paid_chat_duration * $this->price_per_minute / 60) / 100;
			$customer_outcome = $customer->balance < $customer_outcome ? $customer->balance : $customer_outcome;

			$advisor_income = $customer_outcome / 100 * (100 - $this->commission_percentage);

			$customer->update(['balance' => $customer->balance - $customer_outcome]);
			broadcast(new BalanceUpdated($customer));

			$advisor->update(['balance' => $advisor->balance + $advisor_income]);
			broadcast(new BalanceUpdated($advisor));

			// fix duration if session was longer
			if ($this->customer_balance_before === $customer->balance) {
				$paid_chat_duration = 0;
			} else {
				$paid_chat_duration = $customer_outcome / ($this->price_per_minute / 60) * 100;
			}

			$this->update( [
				'is_ended'               => 1,
				'end_reason'             => $reason,
				'ended_at'               => $ended_at,
				'duration'               => $paid_chat_duration,
				'free_chat_duration'     => $free_chat_duration,
				'customer_balance_after' => $customer->balance,
				'advisor_balance_after'  => $advisor->balance
			] );

			$customer->payments()->firstOrCreate([
				'relationship_id' => $this->id,
				'type'            => constants( 'payments.chat_outcome' ),
			], [
				'type'            => constants( 'payments.chat_outcome' ),
				'amount'          => $this->customer_balance_after - $this->customer_balance_before,
				'balance'         => $customer->balance
			]);

			$advisor->payments()->firstOrCreate([
				'relationship_id' => $this->id,
				'type'            => constants( 'payments.chat_income' ),
			], [
				'amount'          => $this->advisor_balance_after - $this->advisor_balance_before,
				'balance'         => $advisor->balance
			] );

			Cache::forget( 'chat_session_id_' . $this->id );
			Cache::forget( 'user-is-busy-' . $customer->id );
			Cache::forget( 'user-is-busy-' . $advisor->id );

			if ($reason === 'customer_inactivity') {
				Cache::forget( 'user-is-online-' . $customer->id );
			} else if ($reason === 'advisor_inactivity' || $reason === 'inactivity_money_back') {
				$expiresAt = Carbon::now()->addHours( 24 );
				Cache::put( 'user-is-offline-' . $advisor->id, true, $expiresAt );

				$advisor->update(['is_logged_in' => 0]);
			}

			if ( $reason === 'inactivity_money_back' ) {
				$payment = new PaymentController();
				$payment->moneyBackForInactivity( $this );
			}

			if ( auth()->user() ) {
				broadcast( new CallEnded( auth()->user(), $this->id, $paid_chat_duration, $reason ) );
			}
		}
	}

	public function isTextExchanged()
	{
		$customer_sent_message = $this->messages()->where([ 'user_id' => $this->customer_id ])->first();
		$advisor_sent_message = $this->messages()->where([ 'user_id' => $this->advisor_id ])->first();

		if ($customer_sent_message && $advisor_sent_message) {
			if (!$this->started_at) {
				$this->update(['started_at' => now()]);

				broadcast(new ChatStarted($this));
			}

			return true;
		}

		return false;
	}

	public function getRemainingPaidSeconds()
	{
		if ($this->isActive()) {
			$price_per_second = $this->price_per_minute / 100 / 60;

			$seconds_remaining = $this->customer->balance / $price_per_second;

			// include used free minutes
			if ($this->advisor->free_minutes_enabled && $this->started_at ) {
				$free_minutes = $this->customer->freeMinutes()->where( [ 'advisor_id' => $this->advisor_id ] )->first();

				if ( $free_minutes ) {
					$free_seconds = $free_minutes->free_seconds - $this->getDuration();

					if ( $free_seconds <= 0 ) {
						$seconds_remaining = $seconds_remaining + $free_minutes->free_seconds;
					} else {
						$seconds_remaining = $seconds_remaining + $free_minutes->free_seconds - $free_seconds;
					}
				}
			}

			$seconds_remaining = floor($seconds_remaining - $this->getDuration());

			return $seconds_remaining > 0 ? $seconds_remaining : 0;
		}

		return 0;
	}

	public function getRemainingFreeSeconds()
	{
		if ($this->isActive() && $this->advisor->free_minutes_enabled) {
			$free_minutes = $this->customer->freeMinutes()->where(['advisor_id' => $this->advisor_id])->first();

			if ($free_minutes) {
				$free_seconds = $free_minutes->free_seconds - $this->getDuration();

				return $free_seconds > 0 ? $free_seconds : 0;
			}
		}

		return 0;
	}

	public function getDuration()
	{
		if ($this->started_at) {
			$end_date = $this->isActive() ? now() : $this->ended_at;

			$duration = $end_date->diffInSeconds($this->started_at);

			// subtract chat pauses
			foreach ($this->pauses as $pause) {
				if ($pause->ended_at) {
					$duration = $duration - $pause->created_at->diffInSeconds($pause->ended_at);
				} else {
					$duration = $duration - $pause->created_at->diffInSeconds(now());
				}
			}

			return $duration;
		}

		return 0;
	}

}

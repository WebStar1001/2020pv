<?php

namespace App\Http\Controllers;

use App\ChatSession;
use App\Events\BalanceUpdated;
use App\Events\PaymentApproved;
use App\Events\PaymentCancelled;
use App\Events\PaymentCreated;
use App\Events\PaymentCreating;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaypalPaymentResource;
use App\PaypalPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\CustomData;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Payment::class, 'payment');
    }

    public function index(Request $request)
    {
        $auth_user = auth()->user();

        $from_date = $request->fromDate ? Carbon::createFromFormat('m/d/Y', $request->fromDate)->startOfDay() : '';
        $to_date = $request->toDate ? Carbon::createFromFormat('m/d/Y', $request->toDate)->endOfDay() : '';

        $payments = $auth_user->payments()
            ->when($from_date, function ($query) use ($from_date) {
                if ($from_date) {
                    $query->where('created_at', '>', $from_date);
                }
            })
            ->when($to_date, function ($query) use ($to_date) {
                if ($to_date) {
                    $query->where('created_at', '<', $to_date);
                }
            })
            ->offset($request->offset)
            ->limit($request->limit)
            ->orderBy($request->sort, $request->order)
            ->get();

        $total = $auth_user->payments()
            ->when($from_date, function ($query) use ($from_date) {
                if ($from_date) {
                    $query->where('created_at', '>', $from_date);
                }
            })
            ->when($to_date, function ($query) use ($to_date) {
                if ($to_date) {
                    $query->where('created_at', '<', $to_date);
                }
            })
            ->count();

        return [
            'data' => new PaymentResource($payments, Auth::user()),
            'total' => $total
        ];
    }

    public function getInvoice(Request $request, $payment_id)
    {
        $auth_user = auth()->user();

        $payment = $auth_user->payments()->findOrFail($payment_id);

        switch ($payment->type) {
            case constants('payments.chat_outcome'):
            case constants('payments.chat_income'):
            case constants('payments.chat_inactivity_money_back'):
            case constants('payments.chat_inactivity_charge'):
                $chat_session = $auth_user->chatSessions()->find($payment->relationship_id);

                $payment->session_with = $auth_user->isAdvisor() ? $chat_session->advisor->display_name : $chat_session->customer->display_name;

                break;
        }

        return response()->json($payment);
    }

    public function payment(Request $request)
    {
        $auth_user = auth()->user();

        broadcast(new PaymentCreating($auth_user));

        if ($request->chat_session_id) {
            $chat_session = ChatSession::find($request->chat_session_id);

            if ($chat_session && $chat_session->isActive()) {
                // end "insufficient_funds" pause
                $chat_session->pauses()
                    ->where(['reason' => constants('chat_pauses.insufficient_funds')])
                    ->whereNull('ended_at')
                    ->update(['ended_at' => now()]);

                // create "adding_funds" pause
                $chat_session->pauses()->create([
                    'reason' => constants('chat_pauses.adding_funds')
                ]);
            }
        }

        $paypal = new ApiContext(
            new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
        );

        if (!env('APP_DEBUG')) {
            $paypal->setConfig(['mode' => 'live']);
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($request->amount . '.00');
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('payment.callback') . '?success=1&redirect_url=' . $request->redirect_url)
            ->setCancelUrl(route('payment.callback') . '?success=0&redirect_url=' . $request->cancel_url);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        $payment->create($paypal);

        $auth_user->paypalPayments()->create([
            'payment_id' => $payment->getId(),
            'amount' => $payment->getTransactions()[0]->amount->total * 100,
            'balance_before' => $auth_user->balance,
            'balance_after' => $auth_user->balance
        ]);

        broadcast(new PaymentCreated($auth_user))->toOthers();

        return response()->json($payment->getApprovalLink());
    }

    public function callback(Request $request)
    {
        $auth_user = auth()->user();
        $redirect_url = $request->redirect_url;

        if ($request->success == '1') {
            $paypal = new ApiContext(
                new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
            );

            if (!env('APP_DEBUG')) {
                $paypal->setConfig(['mode' => 'live']);
            }

            $payment = Payment::get($request->paymentId, $paypal);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);

            try {
                // Take the payment
                $payment_response = $payment->execute($execution, $paypal);

                $paypal_payment = $auth_user->paypalPayments()->where([
                    'payment_id' => $request->paymentId
                ])->first();

                // available states: created, approved, failed, canceled, expired
                if ($payment_response->getState() === 'approved') {
                    $auth_user->balance += $payment_response->getTransactions()[0]->getAmount()->total;
                    $auth_user->save();

                    broadcast(new BalanceUpdated($auth_user));

                    // fix customer balance in case the customer refill balance during chat session
                    if ($auth_user->isCustomer() && $auth_user->isChatting()) {
                        $active_chat = $auth_user->activeChatSession();

                        if ($active_chat) {
                            $active_chat->update([
                                'customer_balance_before' => $active_chat->customer_balance_before + $payment_response->getTransactions()[0]->getAmount()->total
                            ]);
                        }
                    }

                    $auth_user->payments()->create([
                        'relationship_id' => $paypal_payment->id,
                        'type' => constants('payments.fund_added'),
                        'amount' => $payment_response->getTransactions()[0]->getAmount()->total,
                        'balance' => $auth_user->balance
                    ]);

                    if (!env('APP_DEBUG')) {
                        $this->sendFBPurchase($auth_user->payment_email, $payment_response->getTransactions()[0]->getAmount()->total);
                    }
                }

                $transaction_id = $payment_response->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();

                $paypal_payment->update([
                    'transaction_id' => $transaction_id,
                    'payment_response' => $payment_response,
                    'status' => $payment_response->getState() === 'approved',
                    'balance_after' => $auth_user->balance
                ]);

                // available states: created, approved, failed, canceled, expired
                if ($payment_response->getState() === 'approved') {
                    broadcast(new PaymentApproved($auth_user, intval($payment_response->getTransactions()[0]->getAmount()->total)))->toOthers();
                } else {
                    dd('Failed Payment');
                    dd($payment_response->getFailureReason());

                    // TODO: redirect with error message
                }

                $query_separator = '/';
                $redirect_url = $request->redirect_url . $query_separator . intval($payment_response->getTransactions()[0]->getAmount()->total) . $query_separator . $transaction_id;
            } catch (\Exception $e) {
                dd('Failed Payment');
                dd($e);
                dd($e->getMessage());

                // TODO: redirect with error message
            }
        } else {
            broadcast(new PaymentCancelled($auth_user));
        }

        if ($auth_user->isCustomer() && $auth_user->isChatting()) {
            $active_chat = $auth_user->activeChatSession();

            if ($active_chat) {
                // end "adding_funds" pause
                $active_chat->pauses()
                    ->where(['reason' => constants('chat_pauses.adding_funds')])
                    ->whereNull('ended_at')
                    ->update(['ended_at' => now()]);

                // create "insufficient_funds" pause if payment was cancelled and no seconds left
                if ($request->success !== '1' && !$active_chat->getRemainingFreeSeconds() && !$active_chat->getRemainingPaidSeconds()) {
                    if (!$active_chat->isPaused()) {
                        $active_chat->pauses()->create([
                            'reason' => constants('chat_pauses.insufficient_funds')
                        ]);
                    }
                }
            }
        }

        return redirect()->to($redirect_url);
    }

    public function moneyBackForInactivity($chat_session)
    {
        $customer = $chat_session->customer;
        $advisor = $chat_session->advisor;

        $advisor_last_message = $chat_session->messages()->where(['user_id' => $advisor->id])->latest()->first();

        if ($advisor_last_message) {
            $inactivity_seconds = now()->diffInSeconds($advisor_last_message->created_at);

            if ($inactivity_seconds > 600) {
                $inactivity_seconds = 600;
            }

            if ($inactivity_seconds > $chat_session->duration) {
                $inactivity_seconds = $chat_session->duration;
            }

            $price_per_second = $chat_session->price_per_minute / 100 / 60;

            $customer_money_back_amount = $price_per_second * $inactivity_seconds;
            $advisor_inactivity_charge_amount = $customer_money_back_amount * (100 - $chat_session->commission_percentage) / 100;

            // money-back due to the advisor's inactivity
            $customer->update(['balance' => $customer->balance + $customer_money_back_amount]);

            broadcast(new BalanceUpdated($customer));

            $customer->payments()->firstOrCreate([
                'relationship_id' => $chat_session->id,
                'type' => constants('payments.chat_inactivity_money_back'),
            ], [
                'amount' => $customer_money_back_amount,
                'balance' => $customer->balance
            ]);

            // charge money from advisor
            $advisor->update(['balance' => $advisor->balance - $advisor_inactivity_charge_amount]);

            $advisor->payments()->firstOrCreate([
                'relationship_id' => $chat_session->id,
                'type' => constants('payments.chat_inactivity_charge'),
            ], [
                'amount' => -$advisor_inactivity_charge_amount,
                'balance' => $advisor->balance
            ]);
        }
    }

    public function getTransactions(Request $request)
    {
        $transactions = PaypalPayment::with(['user'])
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->where('users.display_name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('users.email', 'LIKE', '%' . $request->search . '%')
            ->orWhere('paypal_payments.transaction_id', 'LIKE', '%' . $request->search . '%')
            ->offset($request->offset)
            ->limit($request->limit)
            ->select('paypal_payments.*')
            ->orderBy($request->sort, $request->order)
            ->get();

        $total = PaypalPayment::with(['user'])
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->where('users.display_name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('users.email', 'LIKE', '%' . $request->search . '%')
            ->orWhere('paypal_payments.transaction_id', 'LIKE', '%' . $request->search . '%')
            ->count();

        return [
            'data' => new PaypalPaymentResource($transactions, Auth::user()),
            'total' => $total
        ];
    }

    private function sendFBPurchase($email, $amount)
    {
        try {
            $access_token = env('FACEBOOK_PIXEL_ACCESS_CODE');
            $pixel_id = env('FACEBOOK_PIXEL_ID');

            $api = Api::init(null, null, $access_token);
            $api->setLogger(new CurlLogger());

            $user_data = (new UserData())
                // It is recommended to send Client IP and User Agent for ServerSide API Events.
                ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
                ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
                ->setEmail($email);

            $custom_data = (new CustomData())
                ->setCurrency('usd')
                ->setValue($amount);

            $event = (new Event())
                ->setEventName('Purchase')
                ->setEventTime(time())
                ->setEventSourceUrl('https://www.gopsys.com/')
                ->setUserData($user_data)
                ->setCustomData($custom_data);

            $events = array();
            array_push($events, $event);

            $request = (new EventRequest($pixel_id))->setEvents($events);
            $request->execute();
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

}

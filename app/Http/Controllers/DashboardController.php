<?php

namespace App\Http\Controllers;

use App\ChatSession;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatSessionResource;
use App\Http\Resources\MissedChatResource;
use App\Http\Resources\ReviewResource;
use App\Message;
use App\MissedChat;
use App\Payment;
use App\PayoutItem;
use App\PaypalPayment;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {

    }

    public function getData(Request $request)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $volume_of_transactions = PaypalPayment::where(['status' => 1])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        } elseif ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        } elseif ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        } elseif ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        } else {
                            $query->where('created_at', 'LIKE', $request->filter . '%');
                        }
                    }
                })
                ->sum('amount');

            $chat_sessions = ChatSession::where('duration', '>', 0)
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->get();

            $commission_earned = 0;

            foreach ($chat_sessions as $chat_session) {
                $session_amount = ($chat_session->duration * $chat_session->price_per_minute / 60) / 100;

                $commission_earned += ($session_amount / 100) * $chat_session->commission_percentage;
            }

            $number_of_advisors = User::where(['role_id' => Role::getAdvisorId()])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->count();

            $number_of_customers = User::where(['role_id' => Role::getCustomerId()])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->count();

            $customers_balance = User::where(['role_id' => Role::getCustomerId()])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('balance');

            $advisors_balance = User::where(['role_id' => Role::getAdvisorId()])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('balance');

            $self_added_funds = Payment::where(['relationship_id' => 0])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $paypal_refunded = PayoutItem::where(['transaction_status' => 'REFUNDED'])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $paypal_onhold = PayoutItem::where(['transaction_status' => 'ONHOLD'])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $paypal_payouts_total = PayoutItem::where(['transaction_status' => 'SUCCESS'])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $active_chat_sessions = ChatSession::where(['is_ended' => 0])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->count();

            $messages = Message::where(function ($query) use ($request) {
                if ($request->filter) {
                    if ($request->filter === 'today') {
                        $query->where('created_at', '>=', now()->startOfDay());
                    }

                    if ($request->filter === 'yesterday') {
                        $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                            ->where('created_at', '<=', now()->yesterday()->endOfDay());
                    }

                    if ($request->filter === 'week') {
                        $query->where('created_at', '>=', now()->startOfWeek());
                    }

                    if ($request->filter === 'month') {
                        $query->where('created_at', '>=', now()->startOfMonth());
                    }
                }
            })->count();

            $total_chat_sessions = ChatSession::where(function ($query) use ($request) {
                if ($request->filter) {
                    if ($request->filter === 'today') {
                        $query->where('created_at', '>=', now()->startOfDay());
                    }

                    if ($request->filter === 'yesterday') {
                        $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                            ->where('created_at', '<=', now()->yesterday()->endOfDay());
                    }

                    if ($request->filter === 'week') {
                        $query->where('created_at', '>=', now()->startOfWeek());
                    }

                    if ($request->filter === 'month') {
                        $query->where('created_at', '>=', now()->startOfMonth());
                    }
                }
            })->count();

            $missed_chat_requests = MissedChat::where(function ($query) use ($request) {
                if ($request->filter) {
                    if ($request->filter === 'today') {
                        $query->where('created_at', '>=', now()->startOfDay());
                    }

                    if ($request->filter === 'yesterday') {
                        $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                            ->where('created_at', '<=', now()->yesterday()->endOfDay());
                    }

                    if ($request->filter === 'week') {
                        $query->where('created_at', '>=', now()->startOfWeek());
                    }

                    if ($request->filter === 'month') {
                        $query->where('created_at', '>=', now()->startOfMonth());
                    }
                }
            })->count();

            $subscribers = User::where(['subscribed' => 1])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'yesterday') {
                            $query->where('created_at', '>=', now()->yesterday()->startOfDay())
                                ->where('created_at', '<=', now()->yesterday()->endOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->count();


            return response()->json([
                'volume_of_transactions' => $volume_of_transactions / 100,
                'commission_earned' => $commission_earned,
                'number_of_advisors' => $number_of_advisors,
                'number_of_customers' => $number_of_customers,
                'customers_balance' => $customers_balance,
                'advisors_balance' => $advisors_balance,
                'self_added_funds' => $self_added_funds,
                'paypal_refunded' => $paypal_refunded / 100,
                'paypal_onhold' => $paypal_onhold / 100,
                'paypal_payouts_total' => $paypal_payouts_total / 100,
                'active_chat_sessions' => $active_chat_sessions,
                'messages' => $messages,
                'total_chat_sessions' => $total_chat_sessions,
                'missed_chat_requests' => $missed_chat_requests,
                'subscribers' => $subscribers
            ]);
        }

        if ($user->isAdvisor()) {
            $sales = $user->payments()
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $chart_data = $user->payments()
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->where('amount', '>', 0)
                ->select('created_at', 'amount')
                ->get();

            $daily_sales = $user->payments()->where('created_at', '>=', now()->subDay())->sum('amount');

            $payout_amount = $user->payoutItems()
                ->where(['transaction_status' => 'SUCCESS'])
                ->where(function ($query) use ($request) {
                    if ($request->filter) {
                        if ($request->filter === 'today') {
                            $query->where('created_at', '>=', now()->startOfDay());
                        }

                        if ($request->filter === 'week') {
                            $query->where('created_at', '>=', now()->startOfWeek());
                        }

                        if ($request->filter === 'month') {
                            $query->where('created_at', '>=', now()->startOfMonth());
                        }
                    }
                })
                ->sum('amount');

            $reviews = $user->reviews()->with(['chatSession', 'customer'])->latest()->limit(5)->get();

            foreach ($reviews as $key => $review) {
                if ($review->session_id) {
                    $chat_session = $review->chatSession;

                    $reviews[$key]['amount_earned'] = $chat_session->advisor_balance_after - $chat_session->advisor_balance_before + $chat_session->moneybackAmount();
                } else {
                    $reviews[$key]['amount_earned'] = $review->emailSubject->amountEarned();
                }
            }

            $missed_chats = $user->missedChats()->latest()->limit(5)->get();

            foreach ($missed_chats as $key => $missed_chat) {
                $missed_chats[$key]->customer['status'] = $missed_chats[$key]->customer->status();
            }

            return response()->json([
                'readings' => $user->chatSessions()->count() + $user->readings,
                'clients' => $user->chatSessions()->distinct('customer_id')->count('customer_id'),
                'lifetime_earnings' => $user->payoutItems()->where(['transaction_status' => 'SUCCESS'])->sum('amount'),
                'sales' => $sales,
                'daily_sales' => $daily_sales,
                'withdrawn' => $payout_amount,
                'reviews' => new ReviewResource($reviews, $user),
                'missed_chats' => new MissedChatResource($missed_chats, $user),
                'chart_data' => $chart_data
            ]);
        }

        if ($user->isCustomer()) {
            $chats = $user->chatSessions()
                ->with(['customer', 'advisor', 'review', 'payments'])
                ->where(['is_ended' => 1])
                ->latest()
                ->limit(5)
                ->get();

            foreach ($chats as $key => $chat) {
                $chats[$key]->display_name = $user->isCustomer() ? $chat->advisor->display_name : $chat->customer->display_name;
                $chats[$key]->amount = $this->getUserAmountEarnedOrSpent($chat, $user);
                $chats[$key]->moneyback_amount = $chat->moneybackAmount();
            }

            $favorite_advisors_ids = $user->favoriteAdvisors()->with('advisor')->pluck('advisor_id')->toArray();

            $favorites_chats = $user->chatSessions()
                ->with(['customer', 'advisor', 'review', 'payments'])
                ->whereIn('advisor_id', $favorite_advisors_ids)
                ->where(['is_ended' => 1])
                ->latest()
                ->limit(5)
                ->get();

            foreach ($favorites_chats as $key => $chat) {
                $favorites_chats[$key]->display_name = $user->isCustomer() ? $chat->advisor->display_name : $chat->customer->display_name;
                $favorites_chats[$key]->amount = $this->getUserAmountEarnedOrSpent($chat, $user);
                $favorites_chats[$key]->moneyback_amount = $chat->moneybackAmount();
            }

            return response()->json([
                'readings' => $user->chatSessions()->count() + $user->readings,
                'favorite_advisors' => $user->favoriteAdvisors()->count(),
                'last_chat_sessions' => new ChatSessionResource($chats, $user),
                'favorites_chat_sessions' => new ChatSessionResource($favorites_chats, $user),
            ]);
        }

        return response()->json([

        ]);
    }

    private function getUserAmountEarnedOrSpent(ChatSession $chat_session, User $user)
    {
        $moneyback_amount = $chat_session->moneybackAmount();

        if ($user->isCustomer()) {
            return $chat_session->customer_balance_after - $chat_session->customer_balance_before + $moneyback_amount;
        } else {
            return $chat_session->advisor_balance_after - $chat_session->advisor_balance_before + $moneyback_amount;
        }
    }

}

<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
	return Auth::check();
});

Broadcast::channel('email_chat', function ($user) {
	return Auth::check();
});

Broadcast::channel('payment', function ($user) {
	return Auth::check();
});

Broadcast::channel('chat.{chat_session}', function ($user, \App\ChatSession $chat_session) {
	return (int) $user->id === (int) $chat_session->customer_id || (int) $user->id === (int) $chat_session->advisor_id;
});

Broadcast::channel('email_chat.{subject}', function ($user, \App\EmailSubject $subject) {
	return (int) $user->id === (int) $subject->customer_id || (int) $user->id === (int) $subject->advisor_id;
});
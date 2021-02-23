<!DOCTYPE html>
<html lang="en">

<head>
    @auth
        <script>
            window.user = @json($user);
            window.user.status = @json($user->status());
            window.activeChatSession =  @json($user->activeChatSession());
            window.busyWithUserId = @json($user->busyWithUserId());
            window.favoriteAdvisors = @json($user->favoriteAdvisorsIds());
            window.blockedAdvisors = @json($user->blockedAdvisorsIds());
            window.activeCall = @json($user->activeCall());
            window.unreadMessages = @json($user->getUnreadMessagesCount());
            window.discountHistory = @json($discount_history);
            window.discountActive = @json($discount_active);

            @if(isset($roles))
                window.roles = @json($roles);
            @endif
        </script>
    @endauth

    <script>
        window.settings = @json($settings);
        window.discount = @json($user_discount);
    </script>

    @include('partials.head')
</head>

<body>

<div id="app">
    <shared-layout error="{{ session('error') }}"></shared-layout>
</div>

{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">Logout</button>
{!! Form::close() !!}

@include('partials.javascripts')


</body>
</html>
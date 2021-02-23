<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
        }
        table tr td {
            padding: 5px;
            border-bottom: 1px solid #adadad;
        }
        .advisor .display-name {
            color: orangered;
        }
        .customer td {
            background: #f8f8f8;
        }
        .alert {
            margin-bottom: 1rem;
            padding: .75rem 1.25rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
    </style>
</head>
<body>

<div class="alert alert-warning">
    Chat started at {{ $chat_session->created_at }}
</div>

<table cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    @foreach($messages as $message)
    <tr class="{{ $message->user->isAdvisor() ? 'advisor' : 'customer' }}">
        <td>
            <div class="display-name">
                {{ $message->user->display_name }}
            </div>
        </td>
        <td>
            {{ $message->message }}
        </td>
        <td align="right">
            {{ $message->created_at->format('h:i:s') }}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
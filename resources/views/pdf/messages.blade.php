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

<h1>{{ $subject->subject }}</h1>

<div class="alert alert-warning">
    Chat started at {{ $subject->created_at }}
</div>

<table cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    @foreach($messages as $message)
    <tr class="{{ $message->sender->isAdvisor() ? 'advisor' : 'customer' }}">
        <td>
            <div class="display-name">
                {{ $message->sender->display_name }}
            </div>
        </td>
        <td>
            @if($message->invoice_amount)
                Invoice - ${{ $message->invoice_amount }}
                {{ $message->invoice_accepted ? ' (Accepted)' : '' }}
                {{ $message->invoice_rejected ? ' (Rejected)' : '' }}
                {{ $message->invoice_cancelled ? ' (Cancelled)' : '' }}
            @else
                {!! $message->message !!}
            @endif
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
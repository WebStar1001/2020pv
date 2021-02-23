<p style="text-align: center; font-size: 16px; color: #000000">
    You have <strong style="color: #EC7A4C">{{ $unread_messages_count }}</strong> unread messages!
</p>

<p style="text-align: center; font-size: 16px;">
    <a style="display: table;
    margin-left: auto;
    margin-right: auto;
    width: 130px;
    height: 38px;
    line-height: 38px;
    text-align: center;
    background: linear-gradient(270deg, #F58233 0%, #C457B7 100%);
    color: #ffffff;
    text-decoration: none;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;"
       href="{{ url('admin/messages') }}"
    >
        Reply
    </a>
</p>
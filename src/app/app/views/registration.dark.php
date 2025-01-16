<extends:sendit:builder subject="User registration"/>
<use:bundle path="sendit:bundle"/>

<block:html>
    <h1>registration</h1>

    <a href="{{ $confirmUrl }}" target="_blank">registration Confirm</a>
</block:html>

<block:text>
    Registration Confirm: {{ $confirmUrl }}
</block:text>


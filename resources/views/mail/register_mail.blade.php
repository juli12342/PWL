Hi {{ $user->name }}, <br>
Silahkan aktivasi email anda dengan klik link di bawah ini <br>
@php
$link = Route('register.verify');
$link .= '?email=';
$link .= $user->email;
$link .= '&otp=';
$link .= $userVerification->otp;

@endphp
<a target="_blank" href="{{ $link }}">Verifikasi Email</a> <br>
Link akan expired pada {{ $userVerification->expired }}
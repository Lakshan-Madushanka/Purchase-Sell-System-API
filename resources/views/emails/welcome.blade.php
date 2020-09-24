
@component('mail::message')
    Hello {{$user->name}}

    Thank you for creating account please verify your account using below link:

    @component('mail::button', ['url' => route('verify', $user->verification_token)])
        Verify Account
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent

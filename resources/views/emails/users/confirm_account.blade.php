@component('mail::message')
# One Last Step

We just need you to confirm your email address to prove that you're a human. You get it, right? Coo.

@component('mail::button', ['url' => url('/register/confirm?confirmation_token=' . $user->confirmation_token)])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

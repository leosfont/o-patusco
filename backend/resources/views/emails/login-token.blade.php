@component('mail::message')
# Hello, {{ $user->name }}!

You can access the platform using the link below:

@component('mail::button', ['url' => $frontendUrl])
Sign in to Platform
@endcomponent

If you did not request this access, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

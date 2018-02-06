
@component('mail::message')
    # Hello {{ $user->name  }}

    You changed your email, so we nned to verify this new  address .Please use the button below

    @component('mail::button', ['url' =>  route('verify', $user->verification_token)  ])
       Verify Account
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent





{{--Hello {{ $user->name  }}--}}

{{--You changed your email, so we nned to verify this new  address .Please use the link below--}}

{{--{{ route('verify', $user->verification_token) }}--}}


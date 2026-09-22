<x-mail::message>
# Login Verification Code

Your One-Time Password (OTP) for logging into LeadPro is:

# {{ $otp }}

This code will expire in 10 minutes. Please do not share it with anyone.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

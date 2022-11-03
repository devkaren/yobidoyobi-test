<x-app::email.layout>
@if ($firstName)
<x-app::email.subtitle>
{{ trans('messages.mail.auth_email_verification.greetings', [
    'first_name' => $firstName,
    'last_name' => $lastName,
]) }}
</x-app::email.subtitle>
@endif
<x-app::email.title>
{{ trans('messages.mail.auth_email_verification.welcome') }}
</x-app::email.title>
<x-app::email.paragraph>
{{ trans('messages.mail.auth_email_verification.verification_requirement') }}
</x-app::email.paragraph>
<x-app::email.action :url="$url">
{{ trans('messages.mail.auth_email_verification.complete_verification') }}
</x-app::email.action>
</x-app::email.layout>

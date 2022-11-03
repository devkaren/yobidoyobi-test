<x-app::email.layout>
@if ($firstName)
<x-app::email.title>
{{ trans('messages.mail.auth_password_reset_link.greetings', [
    'first_name' => $firstName,
    'last_name' => $lastName,
]) }}
</x-app::email.title>
@endif
<x-app::email.paragraph>
{{ trans('messages.mail.auth_password_reset_link.we_have_received_the_request') }}
</x-app::email.paragraph>
<x-app::email.action :url="$url">
{{ trans('messages.mail.auth_password_reset_link.reset_password') }}
</x-app::email.action>
<x-app::email.paragraph>
{{ trans('messages.mail.auth_password_reset_link.ignore_if_you_did_not_request_the_reset') }}
</x-app::email.paragraph>
</x-app::email.layout>

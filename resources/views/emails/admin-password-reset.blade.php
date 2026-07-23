<x-mail::message>
# {{ __('Your password has been reset') }}

{{ __('Hello :name,', ['name' => $user->name]) }}

{{ __('An administrator reset your Classter Portal password. Use the temporary password below to sign in — you will be asked to choose a new password immediately after.') }}

<x-mail::panel>
{{ $temporaryPassword }}
</x-mail::panel>

<x-mail::button :url="route('login')">
{{ __('Sign in') }}
</x-mail::button>

{{ __("If you didn't expect this, contact your administrator.") }}

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>

<x-mail::message>
# {{ __('one-time-passwords::notifications.title') }}

{{ __('one-time-passwords::notifications.intro', ['url' => config('app.url')]) }}

**{{ $oneTimePassword->password }}**

{{ __('one-time-passwords::notifications.outro') }}
</x-mail::message>

@component('mail::message')
# Admin Message

{{ $content['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent

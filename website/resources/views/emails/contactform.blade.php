@component('mail::message')
# Contact Us Message

Email sent through contact page: <br />
<br />

{{ $content['name'] }}
<br />
{{ $content['email'] }}
<br />
{{$content['comments']}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
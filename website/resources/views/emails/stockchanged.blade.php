@component('mail::message')
# {{ $content['title'] }}

Hi {{ $content['name'] }}, Let's show you your stats.

@component('mail::table')
| Trading Account     | Code     | Stock  | Growth    |
|:-----------:|:--------:|:------:|:---------:|
@for($i=0; $i<count($content['info']); $i++)
    |{{ $content['info'][$i]['trading_account'] }}|{{ $content['info'][$i]['stock_symbol'] }}|{{ $content['info'][$i]['stock_name'] }}|{{ $content['info'][$i]['growth'] }}|
@endfor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

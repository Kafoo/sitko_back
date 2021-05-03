@component('mail::message')

{{trans("Hello!", [], $locale)}}

{{ $content }}

@if($url && $url_text)

  @component('mail::button', ['url' => $url])
       
  {{$url_text}}

  @endcomponent
@endif

{{trans("Regards", [], $locale)}}<br>

{{ config('app.name') }}
@endcomponent
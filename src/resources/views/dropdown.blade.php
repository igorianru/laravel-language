@foreach($items as $locale)
  <li>
    <a class="@if(\App::getLocale() == $locale->get('label')) active @endif" href="{{ $locale->get('url') }}">
      {{ strtoupper($locale->get('label')) }}
    </a>
  </li>
@endforeach
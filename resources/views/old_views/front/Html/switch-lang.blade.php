<?php
$trans = LangsArray();
$locale = $trans[app()->getLocale()];
?>
<a class="lang-switcher" title="{{__('app.translate', ['locale'=>$locale])}}" hreflang="{{ $locale }}" href="{{LaravelLocalization::getLocalizedURL($locale, null, [], true)}}">
    <span class="lg-switcher">{{__('app.lang')}}</span>
</a>

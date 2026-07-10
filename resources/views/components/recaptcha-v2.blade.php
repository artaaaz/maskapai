@php
    $siteKey = config('services.recaptcha.site_key');
    $enabled = config('services.recaptcha.enabled', true);
@endphp

@if ($enabled && $siteKey)
    <div class="my-4 flex justify-center">
        <div class="g-recaptcha shadow-sm rounded-lg overflow-hidden border border-slate-200" data-sitekey="{{ $siteKey }}"></div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

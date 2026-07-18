@props(['formId' => 'authForm'])

@php
    $siteKey = config('services.recaptcha.site_key');
    $enabled = config('services.recaptcha.enabled', true);
@endphp

@if ($enabled && $siteKey)
<div class="mb-4">
    <div class="g-recaptcha" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        (function() {
            const form = document.getElementById('{{ $formId }}');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const response = grecaptcha.getResponse();
                if (!response) {
                    e.preventDefault();
                    alert('Harap verifikasi bahwa Anda bukan robot.');
                    return false;
                }
            });
        })();
    </script>
</div>
@endif
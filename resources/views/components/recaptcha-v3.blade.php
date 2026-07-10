@props(['formId' => 'authForm', 'action' => 'login'])

@php
    $siteKey = config('services.recaptcha.site_key');
    $enabled = config('services.recaptcha.enabled', true);
@endphp

@if ($enabled && $siteKey)
<input type="hidden" id="recaptcha-site-key-{{ $formId }}" value="{{ $siteKey }}">
<script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
<script>
    (function() {
        const form = document.getElementById('{{ $formId }}');
        if (!form) return;
        
        const siteKey = '{{ $siteKey }}';
        let recaptchaToken = '';
        let recaptchaReady = false;

        // Load reCAPTCHA token on page load
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, { action: '{{ $action }}' }).then(function(token) {
                    recaptchaToken = token;
                    recaptchaReady = true;
                    addHiddenInput(form, token);
                }).catch(function(err) {
                    console.warn('reCAPTCHA pre-load failed:', err);
                });
            });
        }

        // Refresh token every 2 minutes
        setInterval(function() {
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute(siteKey, { action: '{{ $action }}' }).then(function(token) {
                        recaptchaToken = token;
                        addHiddenInput(form, token);
                    }).catch(function(err) {
                        console.warn('reCAPTCHA refresh failed:', err);
                    });
                });
            }
        }, 120000);

        // Add token on submit (as backup) with timeout fallback
        form.addEventListener('submit', function(e) {
            if (recaptchaToken) {
                addHiddenInput(form, recaptchaToken);
            } else if (typeof grecaptcha !== 'undefined') {
                e.preventDefault();
                
                let submitted = false;
                // Timeout after 2 seconds and submit anyway
                const fallbackTimeout = setTimeout(function() {
                    if (!submitted) {
                        submitted = true;
                        console.warn('reCAPTCHA execution timed out. Submitting form without token.');
                        submitForm();
                    }
                }, 2000);

                grecaptcha.ready(function() {
                    grecaptcha.execute(siteKey, { action: '{{ $action }}' }).then(function(token) {
                        if (!submitted) {
                            submitted = true;
                            clearTimeout(fallbackTimeout);
                            recaptchaToken = token;
                            addHiddenInput(form, token);
                            submitForm();
                        }
                    }).catch(function(error) {
                        console.error('reCAPTCHA execution error:', error);
                        if (!submitted) {
                            submitted = true;
                            clearTimeout(fallbackTimeout);
                            submitForm();
                        }
                    });
                });
            }
        });

        function submitForm() {
            if (form.requestSubmit) {
                form.requestSubmit();
            } else {
                HTMLFormElement.prototype.submit.call(form);
            }
        }

        function addHiddenInput(form, token) {
            let input = form.querySelector('input[name="g-recaptcha-response"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'g-recaptcha-response';
                form.appendChild(input);
            }
            input.value = token;
        }
    })();
</script>
@endif

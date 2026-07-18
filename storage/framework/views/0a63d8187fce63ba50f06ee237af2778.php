<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['formId' => 'authForm']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['formId' => 'authForm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $siteKey = config('services.recaptcha.site_key');
    $enabled = config('services.recaptcha.enabled', true);
?>

<?php if($enabled && $siteKey): ?>
<div class="mb-4">
    <div class="g-recaptcha" data-sitekey="<?php echo e($siteKey); ?>" data-theme="light"></div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        (function() {
            const form = document.getElementById('<?php echo e($formId); ?>');
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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/components/recaptcha-v2.blade.php ENDPATH**/ ?>
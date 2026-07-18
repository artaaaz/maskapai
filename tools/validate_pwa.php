<?php
$manifestPath = __DIR__ . '/../public/manifest.json';
if (!file_exists($manifestPath)) {
    echo "manifest missing: $manifestPath\n";
    exit(1);
}
$json = file_get_contents($manifestPath);
$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "manifest: INVALID JSON - " . json_last_error_msg() . "\n";
    exit(1);
}
echo "manifest: valid JSON\n";
if (isset($data['icons']) && is_array($data['icons'])) {
    foreach ($data['icons'] as $icon) {
        $src = $icon['src'];
        $path = realpath(__DIR__ . '/../public' . $src);
        if ($path && file_exists($path)) {
            echo "icon exists: $src\n";
        } else {
            echo "MISSING icon: $src\n";
        }
    }
} else {
    echo "manifest: no icons array\n";
}

$sw = realpath(__DIR__ . '/../public/sw.js');
if ($sw && file_exists($sw)) {
    echo "sw: exists (public/sw.js)\n";
} else {
    echo "sw: MISSING (public/sw.js)\n";
}

echo "layout registration: ";
$layout = __DIR__ . '/../resources/views/layouts/customer.blade.php';
$content = file_get_contents($layout);
if (strpos($content, "navigator.serviceWorker.register('/sw.js')") !== false) {
    echo "found\n";
} else {
    echo "not found\n";
}

echo "Validation complete.\n";

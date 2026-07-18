<?php
/**
 * Simple icon generator using PHP GD.
 * Generates PNG icons in public/icons for the PWA.
 */

function hex2rgb(string $hex): array {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$dir = __DIR__ . '/../public/icons';
if (!is_dir($dir)) {
    if (!mkdir($dir, 0755, true)) {
        echo "Failed to create directory: $dir\n";
        exit(1);
    }
}

$bgHex = '#2563EB';
$bgRgb = hex2rgb($bgHex);
foreach ($sizes as $s) {
    // If GD is available, use it. Otherwise fall back to a pure-PHP PNG writer (solid color)
    $out = $dir . "/icon-{$s}x{$s}.png";
    if (function_exists('imagecreatetruecolor')) {
        $img = imagecreatetruecolor($s, $s);
        if ($img) {
            $bg = imagecolorallocate($img, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
            imagefilledrectangle($img, 0, 0, $s, $s, $bg);
            $white = imagecolorallocate($img, 255, 255, 255);
            $cx = (int)($s * 0.45);
            $cy = (int)($s * 0.42);
            $crw = (int)($s * 0.42);
            $crh = (int)($s * 0.32);
            imagefilledellipse($img, $cx, $cy, $crw, $crh, $white);
            $points = [
                (int)($s * 0.18), (int)($s * 0.5),
                (int)($s * 0.5),  (int)($s * 0.34),
                (int)($s * 0.5),  (int)($s * 0.6),
            ];
            imagefilledpolygon($img, $points, 3, $white);
            $rx = (int)($s * 0.56);
            $ry = (int)($s * 0.52);
            $rw = max(1, (int)($s * 0.18));
            $rh = max(1, (int)($s * 0.06));
            imagefilledrectangle($img, $rx, $ry, $rx + $rw, $ry + $rh, $white);
            imagepng($img, $out);
            imagedestroy($img);
            echo "Wrote (GD): $out\n";
            continue;
        }
    }

    // Pure PHP PNG writer: solid color square
    $w = $s; $h = $s;
    $r = $bgRgb[0]; $g = $bgRgb[1]; $b = $bgRgb[2];

    // IHDR
    $ihdr = pack('N', $w) . pack('N', $h) . chr(8) . chr(2) . chr(0) . chr(0) . chr(0);

    // raw image data: each scanline starts with filter 0
    $raw = '';
    $px = chr($r) . chr($g) . chr($b);
    for ($y = 0; $y < $h; $y++) {
        $raw .= chr(0) . str_repeat($px, $w);
    }

    $z = gzcompress($raw);

    $png = "\x89PNG\r\n\x1a\n";
    $png .= pack('N', strlen($ihdr)) . 'IHDR' . $ihdr . pack('N', crc32('IHDR' . $ihdr));
    $png .= pack('N', strlen($z)) . 'IDAT' . $z . pack('N', crc32('IDAT' . $z));
    $png .= pack('N', 0) . 'IEND' . pack('N', crc32('IEND'));

    file_put_contents($out, $png);
    echo "Wrote (pure PHP): $out\n";
}

echo "Done.\n";

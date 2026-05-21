<?php

$dir = dirname(__DIR__) . '/public/images/canvas';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$specs = [
    ['pulso', 660, 400],
    ['puntos', 360, 400],
    ['zdd', 420, 360],
];

if (! function_exists('imagecreatetruecolor')) {
    fwrite(STDERR, "GD no está disponible; copia manualmente los PNG a public/images/canvas/\n");
    exit(1);
}

foreach ($specs as [$name, $w, $h]) {
    $im = imagecreatetruecolor($w, $h);
    $bg = imagecolorallocate($im, 238, 242, 255);
    $fg = imagecolorallocate($im, 100, 116, 139);
    imagefill($im, 0, 0, $bg);
    imagestring($im, 5, 10, 20, 'Placeholder: ' . $name . ' (reemplazar)', $fg);
    imagepng($im, $dir . '/' . $name . '.png');
    imagedestroy($im);
}

echo "OK: PNG en {$dir}\n";

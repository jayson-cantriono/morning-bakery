<?php

function uploadProductImage($file, $oldImage = 'placeholder.jpg')
{
    if (empty($file['name'])) {
        return $oldImage;
    }

    $uploadDir = __DIR__ . '/../assets/products/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return $oldImage;
    }

    $newName = time() . '_' . uniqid() . '.jpg';
    $target = $uploadDir . $newName;

    [$width, $height] = getimagesize($file['tmp_name']);

    if ($ext == 'jpg' || $ext == 'jpeg') {
        $source = imagecreatefromjpeg($file['tmp_name']);
    } elseif ($ext == 'png') {
        $source = imagecreatefrompng($file['tmp_name']);
    } elseif ($ext == 'webp') {
        $source = imagecreatefromwebp($file['tmp_name']);
    } else {
        return $oldImage;
    }

    $size = min($width, $height);

    $srcX = ($width - $size) / 2;
    $srcY = ($height - $size) / 2;

    $canvas = imagecreatetruecolor(600, 600);

    $white = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);

    imagecopyresampled(
        $canvas,
        $source,
        0,
        0,
        $srcX,
        $srcY,
        600,
        600,
        $size,
        $size
    );

    imagejpeg($canvas, $target, 90);

    imagedestroy($source);
    imagedestroy($canvas);

    return $newName;
}
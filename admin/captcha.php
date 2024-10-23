<?php
session_start();
$characters = '0123456789';
$captcha_code = '';
for ($i = 0; $i < 5; $i++) {
    $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
}
$_SESSION['captcha'] = $captcha_code;
$width = 120;
$height = 40;
$image = imagecreatetruecolor($width, $height);
$background_color = imagecolorallocate($image, 0, 0, 255); 
$text_color = imagecolorallocate($image, 255, 255, 255); 
imagefill($image, 0, 0, $background_color);
for ($i = 0; $i < 1000; $i++) {
    $noise_color = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
    imagesetpixel($image, (int)rand(0, $width - 1), (int)rand(0, $height - 1), $noise_color);
}
for ($i = 0; $i < 10; $i++) {
    $line_color = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
    imageline($image, (int)rand(0, $width - 1), (int)rand(0, $height - 1), (int)rand(0, $width - 1), (int)rand(0, $height - 1), $line_color);
}
$font_size = rand(18, 22); 
$angle = rand(-15, 15);
$x = rand(10, 30);
$y = rand(25, 35);
$font = 'path_to_your_font.ttf';
if (file_exists($font)) {
    imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font, $captcha_code);
} else {
    imagestring($image, 5, $x, $y - ($font_size / 2), $captcha_code, $text_color);
}
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
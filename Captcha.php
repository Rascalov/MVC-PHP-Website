<?php
// Can't put the captcha in libs, if i do, the captcha will change as soon as you put in the correct captcha (because the file reloads)
Session::init();
// create a color image
$image = imagecreatetruecolor(200, 50);
// give it a white bg
$background_color = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image, 0, 0, 200, 50, $background_color);
$line_color = imagecolorallocate($image, 64, 64, 64);
$number_of_lines = rand(3, 7);
// obscure
for ($i = 0; $i < $number_of_lines; $i++) 
{
    imageline($image, 0, rand() % 50, 250, rand() % 50, $line_color);
}
// add some random blur
$pixel = imagecolorallocate($image, 0, 0, 255);
for ($i = 0; $i < 500; $i++) 
{
    imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
}

// get and loop through combinations
$allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$length = strlen($allowed_letters);
$letter = $allowed_letters[rand(0, $length - 1)];
$word = '';
$text_color = imagecolorallocate($image, 0, 0, 0);
// No. of character in image
$cap_length = 6; 
for ($i = 0; $i < $cap_length; $i++) 
{
    $letter = $allowed_letters[rand(0, $length - 1)];
    imagestring($image, 5,  5 + ($i * 30), 20, $letter, $text_color);
    $word .= $letter;
}
// set the answer as a session variable
Session::set('captcha_string', $word);

// send final image to where the view can get hold of it 
imagepng($image, "captcha_image.png");

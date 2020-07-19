<?php

class captcha
{
    public $width = 85;
    public $height = 25;
    public $size = 15;

    // Set new value
    public function setValue($value, $group = '')
    {
        $_SESSION['captcha'.($group ? '.'.$group : '')] = $value;

        return true;
    }

    // Get saved value
    public function getValue($group)
    {
        $vName = 'captcha'.($group ? '.'.$group : '');

        return $_SESSION[$vname];
    }

    // Check value agains passed
    public function checkValue($value, $group = '')
    {
        $savedValue = getValue($group);

        return ($savedValue && ($savedValue == $value)) ? true : false;
    }

    // Generate new random value
    public function init($group = '')
    {
        return $this->setValue(rand(00000, 99999), $group);
    }

    // Check captcha, for value used default input name "vname"
    public function check($group = '')
    {
        return $this->checkValue($_REQUEST['vname'], $group);
    }

    public function getFont()
    {
        global $config;

        return root.'trash/'.$config['captcha_font'].'.ttf';
    }

    // Generate image for group
    public function generateImage($value, $font)
    {
        $image = imagecreate($this->width, $this->height);

        $bg = imagecolorallocate($image, 255, 255, 255);
        $fg = imagecolorallocate($image, 0, 0, 0);

        imagecolortransparent($image, $bg);
        imageinterlace($image, 1);

        imagettftext($image, $this->size, rand(-5, 5), rand(5, 20), 20, $fg, $font, $value);

        $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagearc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

        $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagearc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

        $dots = $this->width * $this->height / 10;
        for ($i = 0; $i < $dots; $i++) {
            $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, rand(0, $this->width), rand(0, $this->height), $dc);
        }
        imagepng($image);
    }

    // Generate image for specified group
    public function generate($group = '')
    {
        $font = getFont();
        $val = $this->getValue($group);
        if (!$val) {
            $val = 'UNDEF';
        }

        generateImage($val, $font);
    }

    // **************** OLD FUNCTIONALITY ****************

    // Generate captcha image
    public function makeimg($number)
    {
        global $config;

        $image = '';
        $image = imagecreate($this->width, $this->height);

        $bg = imagecolorallocate($image, 255, 255, 255);
        $fg = imagecolorallocate($image, 0, 0, 0);

        imagecolortransparent($image, $bg);
        imageinterlace($image, 1);

        $this->msg = $number;
        imagettftext($image, $this->size, rand(-5, 5), rand(5, 20), 20, $fg, root.'trash/'.$config['captcha_font'].'.ttf', $this->msg);

        $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagearc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

        $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imagearc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

        $dots = $this->width * $this->height / 10;
        for ($i = 0; $i < $dots; $i++) {
            $dc = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, rand(0, $this->width), rand(0, $this->height), $dc);
        }

        imagepng($image);
    }
}

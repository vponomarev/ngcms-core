<?php

class captcha {

	var $width = 85;
	var $height = 25;
	var $size = 15;

	// Set new value
	function setValue($value, $group = '') {

		$_SESSION['captcha' . ($group ? '.' . $group : '')] = $value;

		return true;
	}

	// Get saved value
	function getValue($group) {

		$vName = 'captcha' . ($group ? '.' . $group : '');

		return $_SESSION[$vname];
	}

	// Check value agains passed
	function checkValue($value, $group = '') {

		$savedValue = getValue($group);

		return ($savedValue && ($savedValue == $value)) ? true : false;
	}

	// Generate new random value
	function init($group = '') {

		return $this->setValue(rand(00000, 99999), $group);
	}

	// Check captcha, for value used default input name "vname"
	function check($group = '') {

		return $this->checkValue($_REQUEST['vname'], $group);
	}

	function getFont() {

		global $config;

		return root . 'trash/' . $config['captcha_font'] . '.ttf';

	}

	// Generate image for group
	function generateImage($value, $font) {

		$image = ImageCreate($this->width, $this->height);

		$bg = ImageColorAllocate($image, 255, 255, 255);
		$fg = ImageColorAllocate($image, 0, 0, 0);

		ImageColorTransparent($image, $bg);
		ImageInterlace($image, 1);

		ImageTTFText($image, $this->size, rand(-5, 5), rand(5, 20), 20, $fg, $font, $value);

		$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
		ImageArc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

		$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
		ImageArc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

		$dots = $this->width * $this->height / 10;
		for ($i = 0; $i < $dots; $i++) {
			$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
			ImageSetPixel($image, rand(0, $this->width), rand(0, $this->height), $dc);
		}
		ImagePNG($image);
	}

	// Generate image for specified group
	function generate($group = '') {

		$font = getFont();
		$val = $this->getValue($group);
		if (!$val) {
			$val = 'UNDEF';
		}

		generateImage($val, $font);
	}

	// **************** OLD FUNCTIONALITY ****************

	// Generate captcha image
	function makeimg($number) {

		global $config;

		$image = '';
		$image = ImageCreate($this->width, $this->height);

		$bg = ImageColorAllocate($image, 255, 255, 255);
		$fg = ImageColorAllocate($image, 0, 0, 0);

		ImageColorTransparent($image, $bg);
		ImageInterlace($image, 1);

		$this->msg = $number;
		ImageTTFText($image, $this->size, rand(-5, 5), rand(5, 20), 20, $fg, root . 'trash/' . $config['captcha_font'] . '.ttf', $this->msg);

		$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
		ImageArc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

		$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
		ImageArc($image, rand(0, $this->width), rand(0, $this->height), rand($this->width / 2, $this->width), rand($this->height / 2, $this->height), 0, 360, $dc);

		$dots = $this->width * $this->height / 10;
		for ($i = 0; $i < $dots; $i++) {
			$dc = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
			ImageSetPixel($image, rand(0, $this->width), rand(0, $this->height), $dc);
		}

		ImagePNG($image);
	}
}

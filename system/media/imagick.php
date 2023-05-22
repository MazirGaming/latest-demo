<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Vvveb\System\Image;

class Image {
	private $file;

	private $image;

	private $width;

	private $height;

	private $bits;

	private $mime;

	/**
	 * Constructor.
	 *
	 * @param string $file
	 *
	 * @return void
	 */
	public function __construct($file) {
		if (! extension_loaded('imagick')) {
			exit('Error: PHP Imagick is not installed!');
		}

		if (file_exists($file)) {
			$this->file  = $file;
			$this->image = new Imagick();
			$this->image->readImage($file);

			$this->width  = $this->image->getImageWidth();
			$this->height = $this->image->getImageHeight();
			$this->bits   = $this->image->getImageLength();
			$this->mime   = $this->image->getFormat();
		} else {
			exit('Error: Could not load image ' . $file . '!');
		}
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @return string
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return string
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return string
	 */
	public function getBits() {
		return $this->bits;
	}

	/**
	 * @return string
	 */
	public function getMime() {
		return $this->mime;
	}

	/**
	 * @param string $file
	 * @param quality
	 * @param mixed $quality
	 *
	 * @return void
	 */
	public function save($file, $quality = 90) {
		$this->image->setCompressionQuality($quality);

		$this->image->setImageFormat($this->mime);

		$this->image->writeImage($file);
	}

	/**
	 * @param width
	 * @param height
	 * @param string $default
	 * @param mixed $width
	 * @param mixed $height
	 *
	 * @return void
	 */
	public function resize($width = 0, $height = 0, $default = '') {
		if (! $this->width || ! $this->height) {
			return;
		}

		switch ($default) {
			case 'w':
				$height = $width;

				break;

			case 'h':
				$width = $height;

				break;
		}

		// Image Magick Filter Comparison
		// https://legacy.imagemagick.org/Usage/filter
		// https://urmaul.com/blog/imagick-filters-comparison
		$this->image->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);

		$this->width  = $this->image->getImageWidth();
		$this->height = $this->image->getImageHeight();

		if ($width == $height && $this->width != $this->height) {
			$image = new Imagick();

			if ($this->mime == 'image/png') {
				$background_color = 'transparent';
			} else {
				$background_color = 'white';
			}

			$image->newImage($width, $height, new ImagickPixel($background_color));

			$x = (int)(($width - $this->width) / 2);
			$y = (int)(($height - $this->height) / 2);

			$image->compositeImage($this->image, Imagick::COMPOSITE_OVER, $x, $y);

			$this->image = $image;

			$this->width  = $this->image->getImageWidth();
			$this->height = $this->image->getImageHeight();
		}
	}

	/**
	 * @param string $watermark
	 * @param string $position
	 *
	 * @return void
	 */
	public function watermark($watermark, $position = 'bottomright') {
		$watermark = new Imagick($watermark);

		switch ($position) {
			case 'overlay':
				for ($width = 0; $width < $this->width; $width += $watermark->getImageWidth()) {
					for ($height = 0; $height < $this->height; $height += $watermark->getImageHeight()) {
						$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $width, $height);
					}
				}

				break;

			case 'topleft':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, 0);

				break;

			case 'topcenter':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), 0);

				break;

			case 'topright':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), 0);

				break;

			case 'middleleft':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, intval(($this->height - $watermark->getImageHeight()) / 2));

				break;

			case 'middlecenter':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), intval(($this->height - $watermark->getImageHeight()) / 2));

				break;

			case 'middleright':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), intval(($this->height - $watermark->getImageHeight()) / 2));

				break;

			case 'bottomleft':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, $this->height - $watermark->getImageHeight());

				break;

			case 'bottomcenter':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), $this->height - $watermark->getImageHeight());

				break;

			case 'bottomright':
				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), $this->height - $watermark->getImageHeight());

				break;
		}
	}

	/**
	 * @param top_x
	 * @param top_y
	 * @param bottom_x
	 * @param bottom_y
	 * @param mixed $top_x
	 * @param mixed $top_y
	 * @param mixed $bottom_x
	 * @param mixed $bottom_y
	 *
	 * @return void
	 */
	public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
		$this->image->cropImage($bottom_x - $top_x, $bottom_y - $top_y, $top_x, $top_y);

		$this->width  = $this->image->getImageWidth();
		$this->height = $this->image->getImageHeight();
	}

	/**
	 * @param degree
	 * @param string $color
	 * @param mixed $degree
	 *
	 * @return void
	 */
	public function rotate($degree, $color = 'FFFFFF') {
		$this->image->rotateImage($color, $degree);

		$this->width  = $this->image->getImageWidth();
		$this->height = $this->image->getImageHeight();
	}
}

class Image2 {
	private $file;

	private $image;

	private $width;

	private $height;

	private $bits;

	private $mime;

	public function __construct($file) {
		if (is_file($file)) {
			$this->file   = $file;
			$this->image  = new Imagick($file);
			$this->width  = $this->image->getImageWidth();
			$this->height = $this->image->getImageHeight();
			$this->bits   = $this->image->getImageLength();
			$this->mime   = $this->image->getFormat();
		} else {
			exit('Error: Could not load image ' . $file . '!');
		}
	}

	public function getFile() {
		return $this->file;
	}

	public function getImage() {
		return $this->image;
	}

	public function getWidth() {
		return $this->width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function getBits() {
		return $this->bits;
	}

	public function getMime() {
		return $this->mime;
	}

	public function save($file, $quality = 90) {
		$this->image->setCompressionQuality($quality);
		$this->image->writeImage($file);
	}

	public function resize($width = 0, $height = 0, $default = '') {
		if (! $this->width || ! $this->height) {
			return;
		}
		$this->image->thumbnailImage($width, $height, true, true);
		$this->width  = $width;
		$this->height = $height;
	}

	public function watermark($watermark, $position = 'bottomright') {
		$watermark_pos_x = 0;
		$watermark_pos_y = 0;

		switch ($position) {
			case 'topleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = 0;

				break;

			case 'topright':
				$watermark_pos_x = $this->width - $watermark->getWidth();
				$watermark_pos_y = 0;

				break;

			case 'bottomleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = $this->height - $watermark->getHeight();

				break;

			case 'bottomright':
				$watermark_pos_x = $this->width - $watermark->getWidth();
				$watermark_pos_y = $this->height - $watermark->getHeight();

				break;

			case 'middle':
				$watermark_pos_x = ($this->width - $watermark->getWidth()) / 2;
				$watermark_pos_y = ($this->height - $watermark->getHeight()) / 2;
		}

		$this->image->compositeImage($watermark, imagick::COMPOSITE_OVER, $watermark_pos_x, $watermark_pos_y);
	}

	public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
		$this->width  = $bottom_x - $top_x;
		$this->height = $bottom_y - $top_y;
		$this->image->cropImage($top_x, $top_y, $bottom_x, $bottom_y);
	}

	public function rotate($degree, $color = '#FFFFFF') {
		$rgb = $this->html2rgb($color);
		$this->image->rotateImage(new ImagickPixel($rgb), $degree);
	}

	private function filter($filter) {
		imagefilter($this->image, $filter);
	}

	private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
		$draw = new ImagickDraw();
		$draw->setFontSize($size);
		$draw->setFillColor(new ImagickPixel($this->html2rgb($color)));
		$this->image->annotateImage($draw, $x, $y, 0, $text);
	}

	private function merge($merge, $x = 0, $y = 0, $opacity = 100) {
		$merge->getImage->setImageOpacity($opacity / 100);
		$this->image->compositeImage($merge, imagick::COMPOSITE_ADD, $x, $y);
	}

	private function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) == 6) {
			list($r, $g, $b) = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
		} else {
			return false;
		}
		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return [$r, $g, $b];
	}

	function __destruct() {
	}
}

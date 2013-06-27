<?php
/**
 * Site class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Lib
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */
class Image {

	/**
	 * @param string $path Image path
	 * @return bool|string Image format or false
	 */
	static function GetType($path) {
      $img_file = fopen($path, 'rb');

      flock($img_file, 1);
      $img_sign=fread($img_file, 3);
      flock($img_file, 3);
      fclose($img_file);

      if (strcmp($img_sign, chr(0x47).chr(0x49).chr(0x46))==0) return 'gif';
      elseif (strcmp($img_sign, chr(0x89).chr(0x50).chr(0x4E))==0) return 'png';
      elseif (strcmp($img_sign, chr(0xFF).chr(0xD8).chr(0xFF))==0) return 'jpg';
      else return false;
	}

	/**
	 * @param string $src Original image path
	 * @param string $dest Path to save preview
	 * @param int $imageMaxSize Max size of width and height in pixels
	 */
	static function CreatePreview($src, $dest, $imageMaxSize) {
        $image_format = self::GetType($src);

        if ($image_format == 'gif') $image_resource = imagecreatefromgif($src);
        if ($image_format == 'jpg') $image_resource = imagecreatefromjpeg($src);
        if ($image_format == 'png') $image_resource = imagecreatefrompng($src);

        $image2_width = $image_width = imagesx($image_resource);
        $image2_height = $image_height = imagesy($image_resource);

        if ($image_width > $imageMaxSize || $image_height > $imageMaxSize) {
          if  ($image_height > $image_width) {
              $image2_height = $imageMaxSize;
              $image2_width = $image_width * $imageMaxSize / $image_height;
          } else {
              $image2_width = $imageMaxSize;
              $image2_height = $image_height * $imageMaxSize / $image_width;
          }
        }

        $image2_resource = imagecreatetruecolor($image2_width, $image2_height);
        imagecopyresampled ($image2_resource, $image_resource, 0, 0, 0, 0, $image2_width, $image2_height, $image_width, $image_height);

        if ($image_format == 'gif') imagegif($image2_resource, $dest);
        if ($image_format == 'jpg') imagejpeg($image2_resource, $dest);
        if ($image_format == 'png') imagepng($image2_resource, $dest);

        imagedestroy($image_resource);
        imagedestroy($image2_resource);
    }
}
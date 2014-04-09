<?php

class Urls extends \Phalcon\Mvc\Model
{

	public function setSource()
	{
		return 'urls';
	}
	
	public function initialize()
	{
		
	}
	
	static function createShortUrl($size = 6) {
		$a = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
		$length = strlen($a);
		$url = '';
		for ($i = 0; $i < $size; $i++) {
			$url .= $a[rand(0, $length)];
		}
		return $url;
	}
	
	static function prepareLong($long) {
		return trim($long);
	}
	
	static function checkShortExistance($short) {
		$a = self::findFirstByShort($short);
		return (count($a) > 0) ? $a : false;
	}
	
	static function checkLongExistance($long) {
		$a = self::findFirstByLong($long);
		return (count($a) > 0) ? $a : false;
	}
}

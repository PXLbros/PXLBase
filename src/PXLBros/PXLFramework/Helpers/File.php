<?php namespace PXLBros\PXLFramework\Helpers;

class File
{
	public static function getExtensionFromString($string)
	{
		return substr(strrchr($string, '.'),1);
	}

	public static function getExtension($path)
	{
		if ( function_exists('pathinfo') === false || file_exists($path) === false )
		{
			return self::getExtensionFromString($path);
		}

		return pathinfo($path, PATHINFO_EXTENSION);
	}
}
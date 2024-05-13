<?php

namespace App\Helpers;

class GeneralHelper 
{
	public static function generateFilename(string $base64, string $prefix)
	{
		list($type, $data) = explode(';', $base64);
		list(, $data)      = explode(',', $data);
		$extension = explode('/', $type)[1]; // Extract the file extension

		// Generate a unique filename
		$filename = $prefix.'_' . uniqid() . '.' . $extension;

		return $filename;
	}

	public static function base64Decode(string $base64)
	{
		list($type, $data) = explode(';', $base64);
		list(, $data)      = explode(',', $data);
		$extension = explode('/', $type)[1]; // Extract the file extension

		return $data;
	}
}
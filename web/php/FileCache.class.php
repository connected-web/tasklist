<?php

class FileCache
{
	static $reads = 0;
	static $writes = 0;

	public static function doesNotExist($cacheId)
	{
		return !FileCache::exists($cacheId);
	}

	public static function exists($cacheId)
	{
		$path = FileCache::getFilePathFor($cacheId);
		return file_exists($path);
	}

	public static function ageOfCache($cacheId)
	{
		$age = false;

		$path = FileCache::getFilePathFor($cacheId);
		if(file_exists($path))
		{
			$mtime = filemtime($path);
			$age = time() - $mtime;
		}

		return $age;
	}

	public static function readDataFromCache($cacheId)
	{
		$data = false;

		$path = FileCache::getFilePathFor($cacheId);

		if(file_exists($path))
		{
			$cacheContents = file_get_contents($path);
			$data = unserialize($cacheContents);

			FileCache::$reads++;
		}

		return $data;
	}

	public static function storeDataInCache($data, $cacheId)
	{
		$success = false;

		$path = FileCache::getFilePathFor($cacheId);

		if($data)
		{
			$cacheContents = serialize($data);
			$file = @fopen($path, 'w');
			@fwrite($file, $cacheContents);
			@fclose($file);

			FileCache::$writes++;

			$success = true;
		}

		return $success;
	}

	public static function removeCache($cacheId)
	{
		$path = FileCache::getFilePathFor($cacheId);

		if(file_exists($path))
		{
			unlink($path);
		}
	}

	private static function getFilePathFor($cacheId)
	{
		return __DIR__ . '/../data/' . $cacheId . '.cache';
	}
}

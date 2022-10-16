<?php

/**
 * File Cache Class
 *
 * For reading and writing cached pages from the local file system.
 * 
 * php version 7
 *
 * @category   Caching
 * @package    DFMA
 * @subpackage Caching
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/dfma/
 */

/**
 * File Cache Class
 * 
 * @category   Classes
 * @package    DFMA
 * @subpackage Caching
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/dfma/
 */
class FileCache
{
    var $path;

    /** 
     * Constructor for File Cache
     * 
     * @param string $path The local file path to read and write from
     * 
     * @return void 
     */
    function __construct($path)
    {
        $this->path = $path;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /** 
     * Load method for file cache
     * 
     * @param string  $key    the cache key to find content for
     * @param integer $maxAge the amount of time to check against for expiry
     * 
     * @return void 
     */
    function load($key, $maxAge=0)
    {
        // Create key
        $key = $this->sanitizeKey($key);
        $filePath = $this->path . '/' . $key;

        // Read file
        $contents = @file_get_contents($filePath);

        // Prepare data
        $decoded = unserialize($contents);
        if ($decoded) {
            header('X-Page-Cache-Load: ' . $key . ', key loaded');
        }

        // Check for expiry
        if ($this->expired($decoded, $maxAge)) {
            $decoded = false;
            header('X-Page-Cache-Expired: ' . $key . ', expired');
        }

        return $decoded;
    }

    /** 
     * Store method for file cache
     * 
     * @param string $key   the cache key to store content against
     * @param object $value the object data to store in the cache
     * 
     * @return boolean 
     */
    function store($key, $value)
    {
        // Create key
        $key = $this->sanitizeKey($key);
        $filePath = $this->path . '/' . $key;

        // Prepare data
        if (is_array($value)) {
            $value['time'] = time();
        }
        $encoded = serialize($value);

        // Write file
        $fp = @fopen($filePath, 'w');
        if ($fp) {
            fwrite($fp, $encoded);
            fwrite($fp, '23');
            fclose($fp);
            header('X-Page-Cache-Store: ' . $key . ', stored');
            return true;
        } else {
            header('X-Page-Cache-Error: ' . $key . ', unable to open ' . $filePath);
        }

        return false;
    }

    /** 
     * Check if decoded content has expired against a given maxAge
     * 
     * @param object  $decoded the decoded data object containing a time property
     * @param integer $maxAge  the amount of time to check against for expiry
     * 
     * @return boolean 
     */
    function expired($decoded, $maxAge)
    {
        $result = false;
        if ($maxAge > 0 && is_array($decoded)) {
            $time = isset($decoded['time']) ? $decoded['time'] : 0;
            $now = time();
            $age = $now - $decoded['time'];
            $expires = date('D M j G:i:s T Y', $decoded['time'] + $maxAge);
            $lifeRemaining = ($decoded['time'] + $maxAge) - $now;
            header('X-Page-Cache-Age: ' . $age . ' seconds');
            if ($age > $maxAge) {
                $decoded = false;
                $result = true;
            } else {
                header(
                    'X-Page-Cache-Expires: ' . $expires . ', in ' 
                        . $lifeRemaining . ' seconds'
                );
            }
        }
        return $result;
    }

    /** 
     * Check if decoded content has expired against a given maxAge
     * 
     * @param string $key the key to make file system friendly
     * 
     * @return string 
     */
    function sanitizeKey($key)
    {
        $key = preg_replace("/[^a-zA-Z0-9]/", "-", $key);
        $key = trim($key);
        return $key;
    }
}
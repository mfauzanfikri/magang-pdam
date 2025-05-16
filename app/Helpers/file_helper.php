<?php

if (!function_exists('safe_move_file')) {
    function safe_move_file(string $source, string $destination): bool
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        return rename($source, $destination);
    }
}

if (!function_exists('safe_copy_file')) {
    function safe_copy_file(string $source, string $destination): bool
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        return copy($source, $destination);
    }
}

if(!function_exists('ensure_directory')) {
    /**
     * Ensure a directory exists. Optionally create it.
     *
     * @param string $path The path to check.
     * @param bool $create Whether to create the directory if it doesn't exist.
     * @param int $permissions Permissions to use when creating the directory.
     * @return bool True if the directory exists (or was created), false otherwise.
     */
    function ensure_directory(string $path, bool $create = false, int $permissions = 0777): bool
    {
        if(is_dir($path)) {
            return true;
        }
        
        if($create) {
            return mkdir($path, $permissions, true);
        }
        
        return false;
    }
}

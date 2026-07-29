<?php

if (! function_exists('versioned_asset')) {
    function versioned_asset(string $path): string
    {
        $fullPath = public_path($path);

        $version = is_file($fullPath) ? filemtime($fullPath) : time();

        return asset($path).'?v='.$version;
    }
}

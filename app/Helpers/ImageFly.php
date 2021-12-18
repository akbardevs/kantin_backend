<?php

use Laravel\Nova\Fields\Image;

if (!function_exists('imageFly')) {
    /**
     * Get image and resize on the fly
     * @example: <img src="{{ asset(imageFly($user->photo, [385, 385])) }}" alt="" width="100%">
     * @param  string $path      relative path to file
     * @param  array  $dimension ['width', 'height'] of the image
     * @return string            relative path to the resized image
     */
    function imageFly($path, array $dimension)
    {
        if (file_exists($path)) {
            $image = Image::make($path);
            $resizedImagePath = $image->dirname
                . '/' . $image->filename
                . '_' . implode('_', $dimension)
                . '.' . $image->extension;
            if (!file_exists($resizedImagePath)) {
                $image->fit($dimension[0], $dimension[1]);
                $image->save($resizedImagePath);
            }
            return $resizedImagePath;
        }
        return '';
    }
}
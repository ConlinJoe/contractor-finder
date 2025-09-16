<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get the URL for an image in the resources/images directory
     */
    public static function url(string $path): string
    {
        return asset("images/{$path}");
    }

    /**
     * Get placeholder image URL
     */
    public static function placeholder(string $text = 'Image', int $width = 400, int $height = 300): string
    {
        return "https://via.placeholder.com/{$width}x{$height}/f3f4f6/6b7280?text=" . urlencode($text);
    }

    /**
     * Get hero background image
     */
    public static function heroBackground(): string
    {
        return self::url('heroes/construction-workers.jpg');
    }

    /**
     * Get platform logo
     */
    public static function platformLogo(string $platform): string
    {
        return self::url("platforms/{$platform}.png");
    }

    /**
     * Get testimonial avatar
     */
    public static function testimonialAvatar(string $name): string
    {
        return self::url("testimonials/{$name}.jpg");
    }
}

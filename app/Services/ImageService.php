<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Compress and store the uploaded image.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $disk
     * @param int $maxWidth
     * @param int $quality
     * @return string
     */
    public static function compressAndStore(UploadedFile $file, string $folder, string $disk = 'public', int $maxWidth = 1200, int $quality = 80): string
    {
        try {
            // Determine available driver
            $driver = null;
            if (extension_loaded('gd')) {
                $driver = new \Intervention\Image\Drivers\Gd\Driver();
            } elseif (extension_loaded('imagick')) {
                $driver = new \Intervention\Image\Drivers\Imagick\Driver();
            }

            if (!$driver) {
                // Fallback: store original if no driver is available
                return $file->store($folder, $disk);
            }

            $manager = new ImageManager($driver);
            $image = $manager->read($file->getRealPath());

            // Resize if larger than max width
            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            // Encode as JPEG with quality
            $encoded = $image->toJpeg($quality);

            $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.jpg';
            $path = $folder . '/' . $filename;

            Storage::disk($disk)->put($path, (string) $encoded);

            return $path;
        } catch (\Exception $e) {
            // In case of any error during processing, fall back to default storage
            return $file->store($folder, $disk);
        }
    }
}

<?php

namespace App\Libraries;

use App\Enums\ImageSize;

class ImageProcessor
{
    protected array $sizes;

    public function __construct()
    {
        $this->sizes = ImageSize::withDimensions();
    }

    public function process(string $filename): array
    {
        $uploadPath = FCPATH . 'uploads/';
        $originalFile = $uploadPath . $filename;
        
        if (!file_exists($originalFile)) {
            return ['full' => 'uploads/' . $filename];
        }

        $pathInfo = pathinfo($filename);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        $processed = [
            ImageSize::FULL->value => 'uploads/' . $filename
        ];

        foreach ($this->sizes as $sizeName => $dimensions) {
            $newFilename = $baseFilename . '-' . $sizeName . '.' . $extension;
            $newPath = $uploadPath . $newFilename;

            try {
                \Config\Services::image()
                    ->withFile($originalFile)
                    ->fit($dimensions['width'], $dimensions['height'], 'center')
                    ->save($newPath, 80);

                $processed[$sizeName] = 'uploads/' . $newFilename;
            } catch (\Exception $e) {
                log_message('error', 'Image processing error for ' . $sizeName . ': ' . $e->getMessage());
            }
        }

        return $processed;
    }

    public function deleteAllSizes(string $filepath): void
    {
        if (file_exists(FCPATH . $filepath)) {
            unlink(FCPATH . $filepath);
        }

        $pathInfo = pathinfo($filepath);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = FCPATH . $pathInfo['dirname'];

        foreach (array_keys($this->sizes) as $sizeName) {
            $sizeFile = $directory . '/' . $baseFilename . '-' . $sizeName . '.' . $extension;
            if (file_exists($sizeFile)) {
                unlink($sizeFile);
            }
        }
    }

    public function getImageUrl(string $filepath, string $size = 'full'): string
    {
        if ($size === ImageSize::FULL->value) {
            return base_url($filepath);
        }

        $pathInfo = pathinfo($filepath);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = $pathInfo['dirname'];

        $sizeFile = $directory . '/' . $baseFilename . '-' . $size . '.' . $extension;

        if (file_exists(FCPATH . $sizeFile)) {
            return base_url($sizeFile);
        }

        return base_url($filepath);
    }

    public function getSizes(): array
    {
        return array_merge([ImageSize::FULL->value], array_keys($this->sizes));
    }

    public function getAvailableSizes(string $filepath): array
    {
        $available = [ImageSize::FULL->value => base_url($filepath)];

        $pathInfo = pathinfo($filepath);
        $baseFilename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = FCPATH . $pathInfo['dirname'];

        foreach (array_keys($this->sizes) as $sizeName) {
            $sizeFile = $directory . '/' . $baseFilename . '-' . $sizeName . '.' . $extension;
            if (file_exists($sizeFile)) {
                $available[$sizeName] = base_url(str_replace(FCPATH, '', $sizeFile));
            }
        }

        return $available;
    }

    public function optimizeImage(string $filepath, int $quality = 80): bool
    {
        if (!file_exists(FCPATH . $filepath)) {
            return false;
        }

        try {
            \Config\Services::image()
                ->withFile(FCPATH . $filepath)
                ->save(FCPATH . $filepath, $quality);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Image optimization error: ' . $e->getMessage());
            return false;
        }
    }
}
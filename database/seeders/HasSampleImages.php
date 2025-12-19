<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;

trait HasSampleImages
{
    /**
     * Get list of available sample images from storage by type, waste type and role
     *
     * @param string $type Type of images: 'posts', 'avatars', 'company-logos'
     * @param string|null $wasteTypeName Name of waste type (for posts)
     * @param string|null $role User role: 'waste_company' or 'scrap_dealer' (for posts)
     * @return array
     */
    protected function getAvailableImages(string $type = 'posts', ?string $wasteTypeName = null, ?string $role = null): array
    {
        // Map waste type names to folder names (remove Vietnamese characters)
        $folderMapping = [
            'Thiếc' => 'Thiec',
            'Hợp kim' => 'Hopkim',
            'Chì' => 'Chi',
            'Niken' => 'Niken',
            'Sắt' => 'Sat',
            'Nhôm' => 'Nhom',
            'Đồng' => 'Dong',
            'Inox' => 'Inox',
            'Kẽm' => 'Kem',
            'Cao su' => 'Caosu',
            'Giấy' => 'Giay',
            'Nhựa' => 'Nhua',
        ];

        // Map role names to folder names
        $roleMapping = [
            'waste_company' => 'wastecompany',
            'scrap_dealer' => 'scrapdealer',
        ];

        // If waste type and role are provided for posts, use specific subfolder
        if ($type === 'posts' && $wasteTypeName && $role && isset($folderMapping[$wasteTypeName]) && isset($roleMapping[$role])) {
            $folderName = $folderMapping[$wasteTypeName];
            $roleFolder = $roleMapping[$role];
            $storagePath = storage_path("app/public/posts/{$folderName}/{$roleFolder}");

            if (!File::exists($storagePath)) {
                // Fallback to general waste type folder (without role)
                $storagePath = storage_path("app/public/posts/{$folderName}");
            }

            if (!File::exists($storagePath)) {
                // Fallback to general posts folder
                $storagePath = storage_path("app/public/{$type}");
            }

            if (!File::exists($storagePath)) {
                return [];
            }

            $files = File::files($storagePath);
            $images = [];

            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    // Return relative path for storage
                    if (strpos($storagePath, "/{$roleFolder}") !== false) {
                        $images[] = "posts/{$folderName}/{$roleFolder}/" . $file->getFilename();
                    } else {
                        $images[] = "posts/{$folderName}/" . $file->getFilename();
                    }
                }
            }

            return $images;
        }

        // If only waste type is provided (backward compatibility)
        if ($type === 'posts' && $wasteTypeName && isset($folderMapping[$wasteTypeName])) {
            $folderName = $folderMapping[$wasteTypeName];
            $storagePath = storage_path("app/public/posts/{$folderName}");

            if (!File::exists($storagePath)) {
                // Fallback to general posts folder
                $storagePath = storage_path("app/public/{$type}");
            }

            if (!File::exists($storagePath)) {
                return [];
            }

            $files = File::files($storagePath);
            $images = [];

            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    // Return relative path for storage (posts/Thiec/filename.jpg)
                    $images[] = "posts/{$folderName}/" . $file->getFilename();
                }
            }

            return $images;
        }

        // Default behavior for other types (avatars, company-logos)
        $storagePath = storage_path("app/public/{$type}");

        if (!File::exists($storagePath)) {
            return [];
        }

        $files = File::files($storagePath);
        $images = [];

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // Return relative path for storage (type/filename.jpg)
                $images[] = "{$type}/" . $file->getFilename();
            }
        }

        return $images;
    }

    /**
     * Get random images from available sample images
     *
     * @param array $images Available images
     * @param int $count Number of images to get
     * @return string|null JSON encoded array of image paths
     */
    protected function getRandomImages(array $images, int $count = 1): ?string
    {
        if (empty($images)) {
            return null;
        }

        $count = min($count, count($images));
        $selected = array_rand(array_flip($images), $count);

        if ($count === 1) {
            $selected = [$selected];
        }

        return json_encode($selected);
    }

    /**
     * Get a single random image from available sample images
     *
     * @param array $images Available images
     * @return string|null Image path
     */
    protected function getRandomImage(array $images): ?string
    {
        if (empty($images)) {
            return null;
        }

        return $images[array_rand($images)];
    }
}

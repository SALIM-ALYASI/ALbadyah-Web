<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * رفع صورة واحدة وحفظها
     */
    public static function uploadSingleImage(UploadedFile $image, string $folder): string
    {
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs($folder, $imageName, 'public');
        return $imagePath;
    }

    /**
     * حذف صورة من التخزين
     */
    public static function deleteImage(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        $fullPath = storage_path('app/public/' . $imagePath);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * الحصول على رابط الصورة
     */
    public static function getImageUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        return asset('storage/' . $imagePath);
    }

    /**
     * الحصول على مصفوفة من روابط الصور
     */
    public static function getImageUrls(array $imagePaths): array
    {
        return array_map(function($path) {
            return self::getImageUrl($path);
        }, array_filter($imagePaths));
    }

    /**
     * رفع عدة صور
     */
    public static function uploadMultipleImages(array $images, string $folder): array
    {
        $uploadedPaths = [];
        
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $uploadedPaths[] = self::uploadSingleImage($image, $folder);
            }
        }
        
        return $uploadedPaths;
    }
}

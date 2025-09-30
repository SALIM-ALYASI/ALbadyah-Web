<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * الحصول على رابط الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @param string $defaultImage
     * @return string
     */
    public static function getImageUrl($imagePath = null, $imageUrl = null, $defaultImage = 'images/default-placeholder.jpg')
    {
        // إذا كان هناك مسار صورة محفوظة محلياً
        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // إذا كان هناك مسار صورة في storage (للتوافق مع النظام القديم)
        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }
        
        // إذا كان هناك رابط صورة خارجي
        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }
        
        // إرجاع الصورة الافتراضية
        return asset($defaultImage);
    }
    
    /**
     * التحقق من وجود الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @return bool
     */
    public static function hasImage($imagePath = null, $imageUrl = null)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return true;
        }
        
        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return true;
        }
        
        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * الحصول على معلومات الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @return array
     */
    public static function getImageInfo($imagePath = null, $imageUrl = null)
    {
        $info = [
            'url' => self::getImageUrl($imagePath, $imageUrl),
            'has_image' => self::hasImage($imagePath, $imageUrl),
            'type' => null,
            'size' => null,
            'alt' => 'صورة المحافظة'
        ];
        
        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            $info['type'] = 'local';
            $info['size'] = filesize(storage_path('app/public/' . $imagePath));
        } elseif ($imageUrl) {
            $info['type'] = 'external';
        }
        
        return $info;
    }
    
    /**
     * حذف الصورة المحلية
     *
     * @param string $imagePath
     * @return bool
     */
    public static function deleteImage($imagePath)
    {
        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return unlink(storage_path('app/public/' . $imagePath));
        }
        
        return false;
    }
    
    /**
     * إنشاء صورة افتراضية
     *
     * @param string $text
     * @param int $width
     * @param int $height
     * @return string
     */
    public static function createDefaultImage($text = 'صورة غير متوفرة', $width = 300, $height = 200)
    {
        // إنشاء صورة افتراضية باستخدام GD
        $image = imagecreate($width, $height);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        
        // إضافة النص
        $font = 5; // خط افتراضي
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $font, $x, $y, $text, $textColor);
        
        // حفظ الصورة
        $filename = 'default_' . time() . '.png';
        $path = storage_path('app/public/images/defaults/' . $filename);
        
        // إنشاء المجلد إذا لم يكن موجوداً
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        imagepng($image, $path);
        imagedestroy($image);
        
        return 'images/defaults/' . $filename;
    }
}
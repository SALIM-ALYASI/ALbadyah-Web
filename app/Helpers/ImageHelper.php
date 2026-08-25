<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * تخزين ملف مرفوع في التخزين العام.
     */
    public static function storeUploadedImage(UploadedFile $image, string $directory, ?string $oldPath = null): string
    {
        if ($oldPath) {
            self::deleteImage($oldPath);
        }

        return $image->store($directory, ['disk' => 'public']);
    }

    /**
     * تخزين صورة ممررة كنص Base64.
     *
     * @throws \InvalidArgumentException
     */
    public static function storeBase64Image(string $base64Image, string $directory, ?string $oldPath = null): string
    {
        if ($oldPath) {
            self::deleteImage($oldPath);
        }

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/', $base64Image, $matches) !== 1) {
            throw new \InvalidArgumentException('الصورة المرسلة ليست بصيغة base64 صحيحة.');
        }

        $mimeType = $matches[1];
        $encodedData = $matches[2];

        $extension = self::extensionFromMime($mimeType);
        if (!$extension) {
            throw new \InvalidArgumentException('نوع الصورة غير مدعوم.');
        }

        $binaryData = base64_decode($encodedData, true);
        if ($binaryData === false) {
            throw new \InvalidArgumentException('تعذر فك تشفير الصورة.');
        }

        $directory = trim($directory, '/');
        $filename = Str::uuid() . '.' . $extension;
        $path = $directory ? "{$directory}/{$filename}" : $filename;

        Storage::disk('public')->put($path, $binaryData);

        return $path;
    }

    /**
     * الحصول على رابط الصورة.
     */
    public static function getImageUrl($imagePath = null, $imageUrl = null, $defaultImage = 'images/default-placeholder.jpg')
    {
        $imagePath = self::normalizePath($imagePath);

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->url($imagePath);
        }

        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }

        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL) && self::looksLikeImageUrl($imageUrl)) {
            return self::correctImageUrl($imageUrl);
        }

        return asset($defaultImage);
    }

    /**
     * تصحيح رابط الصورة الخارجي.
     */
    public static function correctImageUrl($imageUrl)
    {
        if (strpos($imageUrl, 'localhost') !== false || strpos($imageUrl, '127.0.0.1') !== false) {
            if (preg_match('/\/storage\/(.+)$/', $imageUrl, $matches)) {
                return asset('storage/' . $matches[1]);
            }
        }

        $parsedUrl = parse_url($imageUrl);

        // رابط خارجي كامل وسليم (له scheme وhost فعليين، مثل Wikimedia) —
        // يرجع كما هو بدون تعديل. التعديل السابق كان يكسر أي رابط خارجي
        // بأخذ الـ path فقط وإعادة بنائه كملف محلي داخل storage/.
        if (!empty($parsedUrl['scheme']) && !empty($parsedUrl['host'])) {
            return $imageUrl;
        }

        // غير هذا: مسار محلي فقط بدون host — نفترضه ملف داخل storage
        if (isset($parsedUrl['path'])) {
            $path = ltrim($parsedUrl['path'], '/');
            if (strpos($path, 'storage/') === 0) {
                $path = substr($path, 8);
            }

            return asset('storage/' . $path);
        }

        return $imageUrl;
    }

    /**
     * تحقق سريع (بدون طلب شبكة) إن الرابط يشير فعليًا لملف صورة، لا صفحة ويب
     * عادية (رابط موقع فندق، رابط بحث خرائط جوجل...). يعتمد على امتداد
     * المسار فقط - كافٍ لمنع تخزين روابط غير صورة في حقول image_url.
     */
    public static function looksLikeImageUrl(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        return (bool) preg_match('/\.(jpe?g|png|gif|webp|avif|svg|bmp)$/i', $path);
    }

    /**
     * التحقق من وجود الصورة.
     */
    public static function hasImage($imagePath = null, $imageUrl = null)
    {
        $imagePath = self::normalizePath($imagePath);

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return true;
        }

        if ($imagePath && file_exists(public_path($imagePath))) {
            return true;
        }

        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return true;
        }

        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL) && self::looksLikeImageUrl($imageUrl)) {
            return true;
        }

        return false;
    }

    /**
     * الحصول على معلومات الصورة.
     */
    public static function getImageInfo($imagePath = null, $imageUrl = null)
    {
        $imagePath = self::normalizePath($imagePath);

        $info = [
            'url' => self::getImageUrl($imagePath, $imageUrl),
            'has_image' => self::hasImage($imagePath, $imageUrl),
            'type' => null,
            'size' => null,
            'alt' => 'صورة المحافظة',
        ];

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $info['type'] = 'local';
            $info['size'] = Storage::disk('public')->size($imagePath);
        } elseif ($imageUrl) {
            $info['type'] = 'external';
        }

        return $info;
    }

    /**
     * حذف الصورة من التخزين.
     */
    public static function deleteImage(?string $imagePath): bool
    {
        $imagePath = self::normalizePath($imagePath);

        $deleted = false;

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $deleted = Storage::disk('public')->delete($imagePath) || $deleted;
        }

        if ($imagePath && file_exists(public_path($imagePath))) {
            $deleted = @unlink(public_path($imagePath)) || $deleted;
        }

        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            $deleted = @unlink(storage_path('app/public/' . $imagePath)) || $deleted;
        }

        return $deleted;
    }

    /**
     * إنشاء صورة افتراضية.
     */
    public static function createDefaultImage($text = 'صورة غير متوفرة', $width = 300, $height = 200): string
    {
        $image = imagecreate($width, $height);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);

        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;

        imagestring($image, $font, $x, $y, $text, $textColor);

        $filename = 'defaults/default_' . time() . '.png';
        $fullPath = storage_path('app/public/' . $filename);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        imagepng($image, $fullPath);
        imagedestroy($image);

        return $filename;
    }

    /**
     * تطبيع المسار ليتوافق مع التخزين العام.
     */
    public static function normalizePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = ltrim($path, '/');

        if (strpos($path, 'storage/') === 0) {
            $path = substr($path, 8);
        }

        return $path;
    }

    protected static function extensionFromMime(string $mimeType): ?string
    {
        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => null,
        };
    }
}
<?php

use App\Helpers\ImageHelper;

if (!function_exists('getImageUrl')) {
    /**
     * الحصول على رابط الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @param string $defaultImage
     * @return string
     */
    function getImageUrl($imagePath = null, $imageUrl = null, $defaultImage = 'images/default-placeholder.jpg')
    {
        return ImageHelper::getImageUrl($imagePath, $imageUrl, $defaultImage);
    }
}

if (!function_exists('hasImage')) {
    /**
     * التحقق من وجود الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @return bool
     */
    function hasImage($imagePath = null, $imageUrl = null)
    {
        return ImageHelper::hasImage($imagePath, $imageUrl);
    }
}

if (!function_exists('getImageInfo')) {
    /**
     * الحصول على معلومات الصورة
     *
     * @param string|null $imagePath
     * @param string|null $imageUrl
     * @return array
     */
    function getImageInfo($imagePath = null, $imageUrl = null)
    {
        return ImageHelper::getImageInfo($imagePath, $imageUrl);
    }
}

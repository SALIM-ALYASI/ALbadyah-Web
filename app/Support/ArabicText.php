<?php

namespace App\Support;

class ArabicText
{
    /**
     * توحيد صيغ الألف والياء والتشكيل حتى يتطابق البحث بغض النظر
     * عن اختلاف كتابة المستخدم (أ/إ/آ/ٱ ← ا، ى ← ي).
     */
    public static function normalize(?string $value): string
    {
        if (! $value) {
            return '';
        }

        $value = preg_replace('/[\x{064B}-\x{0652}]/u', '', $value); // إزالة التشكيل
        $value = preg_replace('/[أإآٱ]/u', 'ا', $value);
        $value = str_replace('ى', 'ي', $value);
        $value = str_replace('ة', 'ه', $value);

        return mb_strtolower(trim($value));
    }

    public static function contains(?string $haystack, ?string $needle): bool
    {
        if (! $needle) {
            return true;
        }

        return str_contains(static::normalize($haystack), static::normalize($needle));
    }
}

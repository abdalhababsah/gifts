<?php

declare(strict_types=1);

namespace App\Support;

trait LocalizesFields
{
    protected function currentLang(): string
    {
        return app()->getLocale() === 'ar' ? 'ar' : 'en';
    }

    /**
     * Return {base}_{ar|en} with fallback to {base}_en, then {base}.
     */
    protected function pickLocalized($model, string $base): mixed
    {
        $lang = $this->currentLang();
        $field = "{$base}_{$lang}";
        $fallbackField = "{$base}_en";

        $get = static function ($src, $key) {
            if (is_array($src) && array_key_exists($key, $src)) return $src[$key];
            if (is_object($src) && isset($src->{$key})) return $src->{$key};
            return null;
        };

        return $get($model, $field)
            ?? $get($model, $fallbackField)
            ?? $get($model, $base);
    }
}

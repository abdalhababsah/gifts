<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Get the current locale from the application.
     * This uses the locale already set by the SetLocale middleware.
     * 
     * @return string
     */
    protected function getLocale(): string
    {
        return app()->getLocale();
    }
    
    /**
     * Get localized message based on the current locale.
     * 
     * @param array $messages Array with locale keys and message values
     * @return string
     */
    protected function getLocalizedMessage(array $messages): string
    {
        $locale = $this->getLocale();
        
        return $messages[$locale] ?? $messages['en'];
    }
}
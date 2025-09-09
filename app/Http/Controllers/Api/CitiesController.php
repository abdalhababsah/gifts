<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function cities()
    {
        $locale = app()->getLocale();
        $nameField = $locale === 'ar' ? 'name_ar' : 'name_en';
    
        $cities = City::active()->get(['id', $nameField]);
    
        return response()->json([
            'success' => true,
            'data' => $cities->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->$nameField,
            ]),
        ]);
    }
    
}

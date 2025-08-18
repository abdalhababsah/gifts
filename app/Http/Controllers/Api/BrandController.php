<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends ApiController
{
    /**
     * Display a listing of the brands.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $brands = Brand::active()->orderBy('name_en')->get();
        
        return BrandResource::collection($brands);
    }
}
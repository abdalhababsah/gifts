<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Category::active()->orderBy('name_en')->get();
        
        return CategoryResource::collection($categories);
    }
}
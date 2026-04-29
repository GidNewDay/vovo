<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Позже добавим фильтрацию и сортировку
        $products = Product::query()->paginate(10);

        return response()->json($products);
    }
}

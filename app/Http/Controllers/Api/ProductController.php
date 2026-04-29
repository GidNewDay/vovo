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
        $query = Product::query();

        //1. Поиск по подстроке в названии (регистронезависимый)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            // Используем LIKE с %...% для поиска подстроки
            $query->where('name', 'like', '%' . addcslashes($searchTerm, '%_') . '%');
        }

        //2. Фильтр по цене от и до
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        //3. Фильтр по категории
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->category_id);
        }

        //4. Фильтр по наличию на складе (true/false)
        if ($request->filled('in_stock')) {
            $inStock = filter_var($request->in_stock, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($inStock !== null) {
                $query->where('in_stock', $inStock);
            }
        }

        //5. Фильтр по рейтингу от
        if ($request->filled('rating_from')) {
            $query->where('rating', '>=', (float) $request->rating_from);
        }

        //6. Сортировка
        $sort = $request->sort;
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        // 7. Пагинация (по умолчанию 10 на страницу, можно переопределить через per_page)
        $perPage = (int) $request->get('per_page', 10);
        $perPage = min($perPage, 100); //ограничим максимум 100 записей
        $products = $query->paginate($perPage);

        return response()->json($products);
    }
}

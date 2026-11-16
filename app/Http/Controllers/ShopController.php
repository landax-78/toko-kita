<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        return view('shop.home', [
            'featuredProducts' => Product::with('category')->latest()->take(8)->get(),
            'categories' => Category::withCount('products')->orderBy('name')->take(6)->get(),
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
        ]);
    }

    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category');

        $query = Product::with('category')->latest();
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('sku', 'like', '%' . $keyword . '%');
            });
        }
        if ($categoryId !== null && $categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        return view('shop.index', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'keyword' => $keyword,
            'selectedCategory' => (string) ($categoryId ?? ''),
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}

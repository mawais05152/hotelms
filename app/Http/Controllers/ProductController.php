<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use App\Models\Product;
use App\Models\Category;
use App\Models\MessMenu;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
   }

    public function create() {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required'
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->price = $request->price;
    $product->category_id = $request->category_id;
    $product->save();

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

    public function edit(Product $product) {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required'
    ]);
    $product = Product::findOrFail($product->id);
    $product->name = $request->name;
    $product->price = $request->price;
    $product->category_id = $request->category_id;
    $product->save();

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    //  public function getProductsByCategory($category_id)
    // {
    //     $products = Product::where('category_id', $category_id)->get();
    //     return response()->json($products);
    // }

    public function getVariations($productId)
{
    $product = Product::with('variations:id,product_id,unit,size')->find($productId);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json($product->variations);
}
public function showVariations($id)
{
    $product = Product::with('variations')->findOrFail($id);

    return view('products.partials.variations', compact('product'));
}

//    public function getProductsByCategory($id)
//     {
//         $categoryName = DB::table('categories')->where('id', $id)->value('name');

//         if (strtolower($categoryName) === 'dishes') {
//             $dishes = MessMenu::with('variations')->get();

//             return response()->json($dishes->map(function ($dish) {
//                 return [
//                     'id' => $dish->id,
//                     'name' => $dish->meal_name,
//                     'type' => 'dish',
//                     'variations' => $dish->variations->map(function ($v) {
//                         return [
//                             'id' => $v->id,
//                             'name' => $v->name,
//                             'price' => $v->price,
//                         ];
//                     }),
//                 ];
//             }));
//         } else {
//             $products = Product::with('variations')->where('category_id', $id)->get();

//             return response()->json($products->map(function ($product) {
//                 return [
//                     'id' => $product->id,
//                     'name' => $product->name,
//                     'type' => 'product',
//                     'variations' => $product->variations->map(function ($v) {
//                         return [
//                             'id' => $v->id,
//                             'name' => $v->size . ' (' . $v->unit . ')',
//                             'price' => $v->price,
//                         ];
//                     }),
//                 ];
//             }));
//         }
//     }
}



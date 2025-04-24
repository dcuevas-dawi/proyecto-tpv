<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    // Show all products or filtered products
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $query = Product::where('user_id', auth()->id());

        switch ($filter) {
            case 'all':
                $query->where('active', true);
                break;
            case 'inactive':
                $query->where('active', false);
                break;
            case 'food':
                $query->where('category', 'food')->where('active', true);
                break;
            case 'drink':
                $query->where('category', 'drink')->where('active', true);
                break;
            case 'other':
                $query->where('category', 'other')->where('active', true);
                break;
            default:
                // Por defecto mostramos solo los activos
                $query->where('active', true);
                break;
        }

        $products = $query->orderBy('name')->get();

        return view('products.index', compact('products', 'filter'));
    }

    // Show the form to create a new product
    public function create()
    {
        return view('products.create');
    }

    // Store a new product
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0.01|max:9999.99',
            'category' => 'required|in:food,drink,other'
        ], [
            'name.required' => 'El nombre del producto es obligatorio',
            'name.max' => 'El nombre no puede tener más de 100 caracteres',
            'price.required' => 'El precio es obligatorio',
            'price.numeric' => 'El precio debe ser un número separando los decimales por un punto',
            'price.min' => 'El precio mínimo es 0,01 €',
            'price.max' => 'El precio máximo es 9.999,99 €',
            'category.required' => 'Debes seleccionar una categoría',
            'category.in' => 'La categoría seleccionada no es válida'
        ]);

        $userId = auth()->id();

        // Search for the highest product number that this user has had
        $maxNumber = Product::where('user_id', $userId)->max('product_id') ?? 0;

        // Create the product
        Product::create([
            'user_id' => Auth::id(),
            'product_id' => $maxNumber + 1,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente');
    }

    // Show the form to edit a product
    public function edit($productId)
    {
        // Search for the product by user_id and product_id
        $product = Product::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        return view('products.edit', compact('product'));
    }

    // Update a product
    public function update(Request $request, $productId)
    {
        // Search for the product by user_id and product_id
        $product = Product::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0.01|max:9999.99',
            'category' => 'required|in:food,drink,other'
        ], [
            'name.required' => 'El nombre del producto es obligatorio',
            'name.max' => 'El nombre no puede tener más de 100 caracteres',
            'price.required' => 'El precio es obligatorio',
            'price.numeric' => 'El precio debe ser un número separando los decimales por un punto',
            'price.min' => 'El precio mínimo es 0,01 €',
            'price.max' => 'El precio máximo es 9.999,99 €',
            'category.required' => 'Debes seleccionar una categoría',
            'category.in' => 'La categoría seleccionada no es válida'
        ]);

        // Update the product
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente');
    }

    // Set a product as inactive doing soft delete
    public function destroy($productId)
    {
        // Search for the product by user_id and product_id
        $product = Product::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        $product->active = false;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente');
    }

    // Restore a soft-deleted product
    public function restore($productId)
    {
        // Search for the product by user_id and product_id
        $product = Product::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        $product->active = true;
        $product->save();

        return redirect()->route('products.index', ['filter' => 'active'])->with('success', 'Producto restaurado correctamente');
    }
}

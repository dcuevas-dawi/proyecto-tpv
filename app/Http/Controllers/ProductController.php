<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $query = Product::where('user_id', auth()->id());

        switch ($filter) {
            case 'all':
                // No filter
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

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:food,drink,other',
        ]);

        Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        // Verificar que el producto pertenece al usuario actual
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->with('error', 'No tienes permiso para editar este producto');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Check product owner
        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.index')->with('error', 'No tienes permiso para editar este producto');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:food,drink,other',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'No tienes permiso para eliminar este producto');
        }

        $product->active = false;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente');
    }
    public function restore($id)
    {
        $product = Product::withoutGlobalScope('active')->findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'No tienes permiso para restaurar este producto');
        }

        $product->active = true;
        $product->save();

        return redirect()->route('products.index', ['filter' => 'active'])->with('success', 'Producto restaurado correctamente');
    }
}

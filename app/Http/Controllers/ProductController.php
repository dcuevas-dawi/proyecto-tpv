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

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
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

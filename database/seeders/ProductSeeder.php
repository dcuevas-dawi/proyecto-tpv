<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::latest()->first(); // Last created user id

        // Example products
        $products = [
            ['name' => 'Cerveza', 'description' => 'Cerveza fría', 'price' => 2.50, 'category' => 'drink'],
            ['name' => 'Vino tinto', 'description' => 'Botella de vino tinto', 'price' => 10.00, 'category' => 'drink'],
            ['name' => 'Vino blanco', 'description' => 'Botella de vino blanco', 'price' => 10.00, 'category' => 'drink'],
            ['name' => 'Agua mineral', 'description' => 'Botella de agua', 'price' => 1.00, 'category' => 'drink'],
            ['name' => 'Coca-Cola', 'description' => 'Lata de Coca-Cola', 'price' => 1.50, 'category' => 'drink'],
            ['name' => 'Fanta', 'description' => 'Lata de Fanta', 'price' => 1.50, 'category' => 'drink'],
            ['name' => 'Sandwich de jamón y queso', 'description' => 'Delicioso sandwich', 'price' => 3.00, 'category' => 'food'],
            ['name' => 'Hamburguesa', 'description' => 'Hamburguesa con queso', 'price' => 5.00, 'category' => 'food'],
            ['name' => 'Pizza Margherita', 'description' => 'Pizza clásica de tomate y queso', 'price' => 8.00, 'category' => 'food'],
            ['name' => 'Ensalada César', 'description' => 'Ensalada fresca con pollo y aderezo', 'price' => 7.00, 'category' => 'food'],
            ['name' => 'Patatas fritas', 'description' => 'Patatas fritas crujientes', 'price' => 2.00, 'category' => 'food'],
            ['name' => 'Croquetas', 'description' => 'Croquetas caseras', 'price' => 3.00, 'category' => 'food'],
            ['name' => 'Tarta de queso', 'description' => 'Postre clásico de tarta de queso', 'price' => 4.00, 'category' => 'food'],
            ['name' => 'Café', 'description' => 'Café espresso', 'price' => 1.50, 'category' => 'drink'],
            ['name' => 'Café con leche', 'description' => 'Café con leche caliente', 'price' => 2.00, 'category' => 'drink'],
            ['name' => 'Te helado', 'description' => 'Te helado de limón', 'price' => 2.50, 'category' => 'drink'],
            ['name' => 'Té verde', 'description' => 'Infusión de té verde', 'price' => 2.00, 'category' => 'drink'],
            ['name' => 'Limonada', 'description' => 'Limonada fresca', 'price' => 2.00, 'category' => 'drink'],
            ['name' => 'Bocadillo de calamares', 'description' => 'Bocadillo con calamares fritos', 'price' => 4.50, 'category' => 'food'],
            ['name' => 'Pulpo a la gallega', 'description' => 'Pulpo cocido con pimentón', 'price' => 12.00, 'category' => 'food'],
        ];

        foreach ($products as $product) {
            Product::create([
                'user_id' => $user->id,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category' => $product['category'],
            ]);
        }
    }
}

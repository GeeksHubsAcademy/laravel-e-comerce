<?php
namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getAll()
    {
        try {
            // $products = Product::withTrashed()->get();//me saca también los eliminados

            $products = Product::with('categories')->get();//no saca los eliminados con deleted_at
            return response($products);
        } catch (\Exception $e) {
            return response([
                'error' => $e
            ], 500);
        }
    }
    public function insert(Request $request)
    {
        try {
            $categoriesIds = Category::all()->map(fn ($category) => $category->id)->toArray();
            // $categoriesMapped=$categoriesFiltered->map(fn ($category) =>[
            //     'id'=>$category->id,
            //     'name'=>$category->name
            //     ])->values()->toArray();
            // dd($categoriesMapped);
            // $numbers =new Collection([[1,2],[[3,4],5,6]]) ;
            // dd($numbers->flatten()->sum());
            // dump($categoriesIds);
            // dump(join(',',$categoriesIds));//transforma arrays en strings definiendo un separador lo mismo join que implode
            $body = $request->validate([
                'name' => 'required|string|max:40',
                'price' => 'required|numeric',
                'description' => 'string',
                'categories' => 'required|array|in:' . implode(',', $categoriesIds)
            ]);
            $product = Product::create($body);
            //const product = await Product.create(req.body)
            $product->categories()->attach($body['categories']);
            // product.addCategory(req.body.categories)
            // $product->categories()->create($body['categories']);
            // product.createCategory(req.body.categories)
            return response($product->load('categories'), 201);
        } catch (\Exception $e) {
            return response([
                'error' => $e
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            dd($request);
            $categoriesIds = Category::all()->map(fn ($category) => $category->id)->toArray();
            $body = $request->validate([
                'name' => 'string|max:40',
                'price' => 'numeric',
                'description' => 'string',
                'categories' => 'array|in:' . implode(',', $categoriesIds)

            ]);
            $product = Product::find($id);
            // $product->categories()->detach();//elimina las filas en la tabla intermedia category_product
            $product->update($body);
            // $product->categories()->attach($body['categories']);//añade filas en la tabla intermedia category_product
            if ($request->has('categories')) {
                $product->categories()->sync($body['categories']);
            }
            return response($product->load('categories'));
        } catch (\Exception $e) {
            return response([
                'error' => $e
            ], 500);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        try {
            $request->validate(['img' => 'required|image']);
            // $request->validate(['imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $product = Product::find($id);//buscamos el producto a actualizar la ruta de la imagen
            $imageName = time() . '-' . request()->img->getClientOriginalName();//time() es como Date.now()
            request()->img->move('images/products', $imageName);//mueve el archivo subido al directorio indicado (en este caso public path es dentro de la carpeta public)
            $product->update(['image_path' => $imageName]);//actualizamos el image_path con el nuevo nombre de la imagen
            return response($product);
        } catch (\Exception $e) {
            return response([
                'error' => $e,
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
            return response([
                'message' => 'Producto eliminado con éxito',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => $e,
            ], 500);
        }
    }
    public function restore($id)
    {
        try {
            $products = Product::withTrashed()->where('id', $id)->get();
            // dd($products);
            if ($products->isEmpty() || !$products[0]->trashed()) {
                return response([
                    'message' => 'No se ha encontrado el producto a recuperar'
                ], 400);
            }
            $product = $products[0];
            $product->restore();
            return response([
                'message' => 'Producto recuperado con éxito',
                'product' => $product
            ], 400);
        } catch (\Exception $e) {
            return response([
                'error' => $e,
            ], 500);
        }
    }
}

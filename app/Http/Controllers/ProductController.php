<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function insert(Request $request)
    {
        try {
            $categoriesIds = Category::all()->map(fn ($category) =>$category->id)->toArray();
            // $categoriesMapped=$categoriesFiltered->map(fn ($category) =>[
            //     'id'=>$category->id,
            //     'name'=>$category->name
            //     ])->values()->toArray();
            // dd($categoriesMapped);
            // $numbers =new Collection([[1,2],[[3,4],5,6]]) ;
            // dd($numbers->flatten()->sum());
            // dump($categoriesIds);
            // dump(join(',',$categoriesIds));//transforma arrays en strings definiendo un separador lo mismo join que implode
            $request->validate([
                'name' => 'required|string|max:40',
                'price' => 'required|numeric',
                'description' => 'string',
                'categories' => 'required|array|in:'. implode(',',$categoriesIds)
            ]);
            $body=$request->all();
            $product = Product::create($body);
            //const product = await Product.create(req.body)
            $product->categories()->attach($body['categories']);
            // product.addCategory(req.body.categories)
            // $product->categories()->create($body['categories']);
            // product.createCategory(req.body.categories)
            return response($product->load('categories'),201);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

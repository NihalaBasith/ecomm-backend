<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function AddProduct(Request $request){
        $product = new Product;
    

        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,png,jpeg,gif',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'salesprice' => 'nullable|numeric',
        ]);
    

        $product->name = $request->input('name');
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('products', 'public');
            $product->file_path = $filePath;
        }
    
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->sales_price = $request->input('salesprice', null); 

        $product->save();
    
        return response()->json($product, 201);
    }
    function list(){
        return Product::all();
    }

    public function delete($id) {
        $data = Product::find($id); 
        if ($data) {
            $data->delete(); 
            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200); 
        } else {
            return response()->json([
                'message' => 'Product not found',
            ], 404); 
        }
    }
    public function getProduct($id) {
        $data = Product::find($id);
        return response()->json($data); // Return JSON response
    }
    
    public function updateProduct(Request $request, $id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'sales_price' => 'required|numeric',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);
    
        // Update product fields
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->sales_price = $validatedData['sales_price'];
    
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('products'); 
            $product->file_path = $filePath;
        }
    
        $product->save(); // Save the updated product
    
        return response()->json(['message' => 'Product updated successfully']);
    }

    function search($key){
        $data = Product::where('name','like',"%$key%")->get();
        if( $data){
            return response()->json([
                'data' =>$data,
                'message' => 'Search completed successfully'
            ]);
        }
        return response()->json(['message' => 'No products matched your search'], 404);

    } 
}

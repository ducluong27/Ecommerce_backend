<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'category_id' =>'required|max:191',
            'slug' =>'required|max:191',
            'name' =>'required|max:191',
            'meta_title' =>'required|max:191',
            'brand' =>'required|max:191',
            'sellingPrice' =>'required|max:191',
            'originalPrice' =>'required|max:191',
            'quantity' =>'required|max:4',
            'image' =>'required|image|mimes:jpg,png,jpeg|max:2048',

        ]);

        if($validator->fails()){
            return response()->json([
                'status' =>422,
                'errors' =>$validator->errors(),
            ]);
        }
        else{
            $product = new Product();
            $product->category_id=$request ->input('category_id');
            $product->slug=$request ->input('slug');
            $product->name=$request ->input('name');
            $product->description=$request ->input('description');

            $product->meta_title=$request ->input('meta_title');
            $product->meta_keyword=$request ->input('meta_keyword');
            $product->meta_description=$request ->input('meta_description');

            $product->brand=$request ->input('brand');
            $product->sellingPrice=$request ->input('sellingPrice');
            $product->originalPrice=$request ->input('originalPrice');
            $product->quantity=$request ->input('quantity');

            if($request->hasFile('image')){
                $file= $request->file('image');
                $extension= $file->getClientOriginalExtension();
                $filename= time().'.'.$extension;
                $file->move('uploads/product/',$filename);
                $product->image='uploads/product/'.$filename;
            }

            $product->featured=$request ->input('featured')==true?'1':'0';
            $product->popular=$request ->input('popular')==true?'1':'0';
            $product->status=$request ->input('status')==true?'1':'0';
            $product->save();
            return response()->json([
                'status' =>200,
                'message' => 'Product added successfully'

            ]);
        }
        

    }
    public function index(){
        $product= Product::all();
        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use App\Models\ProductRating;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ProductControlller extends Controller
{
    public function index(Request $request){
        $products = Product::latest('id')->with('product_images');
        
        if($request->get('keyword') != ""){
            $products = $products -> where('title','like','%'.$request->keyword.'%');
        }

        $products = $products->paginate();
        // dd($products);
        $data['products'] = $products;
        return view('supplier.products.list',$data);
    }
    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('supplier.products.create',$data);
    }
    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'status' => 'required|integer|in:0,1', // Ensure status is an integer

        ];
        if(!empty($request -> track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] ='required|numeric';
        $validator = Validator::make($request -> all(),$rules);
        
        if($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();
            
            //save gallery pics 
            if(!empty($request->image_array)){
                foreach($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); 

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //large Image
                        $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                        $destPath = public_path().'/uploads/product/large/'.$imageName;
                        $image = \Intervention\Image\Facades\Image::make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destPath);

                        //small Image
                        $destPath = public_path().'/uploads/product/small/'.$imageName;
                        $image = \Intervention\Image\Facades\Image::make($sourcePath);
                        $image->fit(300, 300);
                        $image->save($destPath);


                //     //large Image
                //     $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                //     $destPath = public_path().'/uploads/product/large/'.$tempImageInfo->name;
                //     $image = Image::make($sourcePath);
                //     $image->resize(1400, null, function ($constraint) {
                //         $constraint->aspectRatio();
                //     });
                //     $image->save($destPath);

                // //small Image
                //     $destPath = public_path().'/uploads/product/small/'.$tempImageInfo->name;
                //     $image = Image::make($sourcePath);
                //     $image->fit(300,300);
                //     $image->save($destPath);
                    } 
        }
            $request->session()->flash('success','Product Added Succesfully');
            return response()->json([
                'status' => true,
                'message' => 'Product Added Succesfully..'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
        // return view('admin.products.create',$data);
    }
    public function edit($id, Request $request){

        $product = Product::find($id);

        if(empty($product)){
            
            return redirect()->route('products.index')->with('error','Product not found');
        }
        //Fetch productimages
        $productImages= ProductImage::where('product_id',$product->id)->get();

        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
        // dd($subCategories);
        

        //Fetch related product
        $relatedProducts =[];   
        if($product->related_products != ''){
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }


        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;

        return view('supplier.products.edit',$data);
    }
    public function update($id, Request $request){
        $product = Product::find($id);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'status' => 'required|integer|in:0,1', // Ensure status is an integer

        ];

        if(!empty($request -> track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] ='required|numeric';
        }
        $validator = Validator::make($request -> all(),$rules);
        
        if($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();

            $request->session()->flash('success','Product Updated Succesfully');
            return response()->json([
                'status' => true,
                'message' => 'Product Updated Succesfully..'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }    
    }
    
    public function destroy($id, Request $request){
        $product = Product::find($id);

        if(empty($product)){
            $request->session()->flash('error','Product not found..');
            return response()->json([
                'status' =>false,
                'notfound' =>true
            ]);
        }
        //images
        $productImages = ProductImage::where('product_id',$id)->get();
        if(!empty($productImages)){
            foreach($productImages as $productImage){
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }
            ProductImage::where('product_id',$id)->delete();
        }
        $product->delete();
        $request->session()->flash('success','Product deleted successfully..');
            return response()->json([
                'status' =>true,
                'message' =>'Product deleted successfully..'
            ]);
    }
    public function getProducts(Request $request){
        $tempProduct = [];
        if($request ->term != ""){
            $products = Product::where('title','like','%'.$request ->term .'%')->get();
            if( $products  != null){
                foreach( $products as $product){
                    $tempProduct[] = array('id' => $product->id,'text' => $product->title);
                }
            }
        }
        // print_r($tempProduct);
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    public function productRatings(Request $request){
        $ratings = ProductRating::select('product_ratings.*', 'products.title as productTitle')->orderBy('product_ratings.created_at', 'DESC');
        //leftjoin  
        $ratings =  $ratings -> leftjoin('products','products.id','product_ratings.product_id');

        if($request->get('keyword') != ""){
            $ratings = $ratings -> orWhere('products.title','like','%'.$request->keyword.'%');
            $ratings = $ratings -> orWhere('product_ratings.username','like','%'.$request->keyword.'%');

        }
        $ratings = $ratings -> paginate(10);
        
        return view('supplier.products.ratings', compact('ratings'));
    }
    public function changeRatingsStatus(Request $request){
        $productRating = ProductRating::find($request->id);
        $productRating->status = $request->status;
        $productRating->save();

        session()->flash('success', 'Status change succesfully');
        return response()->json([
            'status' => true
        ]);
    }
    public function destroyrating($id, Request $request){
        $ratings = ProductRating::find($id);
        if(empty($ratings)){
            $request->session()->flash('error','Product not found..');
            return response()->json([
                'status' =>false,
                'notfound' =>true
            ]);
        }
        $ratings->delete();
        $request->session()->flash('success','Rating deleted successfully..');
            return response()->json([
                'status' =>true,
                'message' =>'Rating deleted successfully..'
            ]);
    }
}
<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands =Brand::latest('id');
        if (($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->keyword.'%');
        }
    
        $brands = Brand::paginate(10);
        return view ('supplier.brands.list',compact('brands'));
      
        }
    public function create(){
        return view ('supplier.brands.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request -> all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required|integer', // Ensure status is an integer

        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = (int) $request->status; // Cast status to integer
            $brand->save();
            
            $request->session()->flash('success','Brand Added Successfully..');
            return response()->json([
                'status' => true,
                'message' =>'Brand Added Succesfully..'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
    }
    public function edit($id,Request $request){
        $brand = Brand ::find($id);
        if(empty($brand)){
            $request->session()->flash('error','Record Not Found');
            return redirect()->route('brands.index');
        }
        $data['brand'] = $brand;
        return view ('supplier.brands.edit',$data);  
    }
    public function destroy($id,Request $request){
        $brand = Brand ::find($id);
        if(empty($brand)){
            return redirect()->route('brands.index');
        }
        $brand->delete();
        $request->session()->flash('success','Brand Deleted Successfully..');
        return response([
            'status' => true,
            'message'=> 'Brand Deleted successfully'
        ]);
    }
    public function update($id,Request $request){
        $brand = Brand ::find($id);
        if(empty($brand)){
            $request->session()->flash('error','Record Not Found');
            return response()->json([
                'status' => false,
                'notFound' =>true
            ]);
            // return redirect()->route('brands.index');
        }
        $validator = Validator::make($request -> all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = (int) $request->status; // Cast status to integer
            $brand->save();

            return response([
                'status' => true,
                'message' =>'Brand Added Succesfully..'
            ]);
        }else{
            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
    
    }

}
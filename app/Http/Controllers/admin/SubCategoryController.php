<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubCategory as SubCategoryModel;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories =SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftjoin('categories','categories.id','sub_categories.category_id');
        //searching keyword code
        if(!empty($request->get('keyword'))){
            $subCategories =$subCategories -> where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories =$subCategories -> orWhere('categories.name','like','%'.$request->get('keyword').'%');

        }
        $subCategories =$subCategories->paginate(10);
        // $data['categories'] = $categories;
        return view ('supplier.sub_category.list',compact('subCategories'));
    }
    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories']  =  $categories;
        return view ('supplier.sub_category.create', $data);
    }
    public function store(Request $request){
        $validator = Validator::make($request -> all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' =>'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success','Sub-Category added Successfully..');
            return response([
                'status' => true,
                'message'=> 'Sub-Category addeed successfully'
            ]);
        }else{
            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
        
        
    }
    public function edit($id,Request $request){

        $subCategory = SubCategory ::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return rediret()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories']  =  $categories;
        $data['subCategory'] = $subCategory;
        return view ('supplier.sub_category.edit', $data);
    }
    public function update($id,Request $request){

        $subCategory = SubCategory ::find($id);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound'=>true
            ]);
            // return rediret()->route('sub-categories.index');
        }

        $validator = Validator::make($request -> all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' =>'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {

        
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success','Sub-Category updated Successfully..');
            return response([
                'status' => true,
                'message'=> 'Sub-Category updated successfully'
            ]);
        }else{
            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
    }
    public function destroy($id,Request $request){
        $subCategory = SubCategory ::find($id);
        if(empty($subCategory)){
            return redirect()->route('sub-categories.index');
        }
        $subCategory->delete();
        $request->session()->flash('success','Sub-Category Deleted Successfully..');
        return response([
            'status' => true,
            'message'=> 'Sub-Category Deleted successfully'
        ]);
    }    
}
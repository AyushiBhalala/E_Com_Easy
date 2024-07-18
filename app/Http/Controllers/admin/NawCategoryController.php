<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;
class NawCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->paginate(10);

        return view('supplier.category.list', compact('categories'));
    }

    public function create()
    {
        // Log::info('CategoryController@create method hit');
        return view('supplier.category.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:categories,slug',
            ]);

            if ($validator->fails()) {
                // dd($validator->errors());
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->image = null; 

            $category->save();

            // Handle image upload if present
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                if ($tempImage) {
                    $extArray = explode('.', $tempImage->name);
                    $ext = end($extArray);

                    $newImageName = $category->id . '.' . $ext;
                    $sPath = public_path('temp/' . $tempImage->name);
                    $dPath = public_path('uploads/category/' . $newImageName);

                    // Copy image file
                    File::copy($sPath, $dPath);

                    // Generate thumbnail
                    $thumbPath = public_path('uploads/category/thumb/' . $newImageName);
                    $img = Image::make($sPath);
                    $img->fit(450, 600, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($thumbPath);

                    // Update category with new image
                    $category->image = $newImageName;
                    $category->save();

                    // Optionally delete temp image
                    $tempImage->delete();
                }
            }

            $request->session()->flash('success', 'Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CategoryController@store: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('supplier.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                if ($tempImage) {
                    $extArray = explode('.', $tempImage->name);
                    $ext = end($extArray); // Corrected from 'last' to 'end'

                    $newImageName = $category->id . '-' . time() . '.' . $ext;
                    $sPath = public_path('temp/' . $tempImage->name);
                    $dPath = public_path('uploads/category/' . $newImageName);

                    if (File::exists($sPath)) {
                        File::copy($sPath, $dPath);

                        // Generate image Thumbnail
                        $thumbPath = public_path('uploads/category/thumb/' . $newImageName);
                        $img = Image::make($dPath); // Use $dPath instead of $sPath for thumbnail creation
                        $img->fit(450, 600, function ($constraint) {
                            $constraint->upsize();
                        });
                        $img->save($thumbPath);

                        $category->image = $newImageName;
                        $category->save();

                        // Delete old Image
                        if (!empty($oldImage)) {
                            File::delete(public_path('uploads/category/thumb/' . $oldImage));
                            File::delete(public_path('uploads/category/' . $oldImage));
                        }
                    } else {
                        throw new \Exception("Source image file does not exist: $sPath");
                    }
                } else {
                    throw new \Exception("Temp image not found for ID: " . $request->image_id);
                }
            }

            $request->session()->flash('success', 'Category updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        // Delete associated images
        if (!empty($category->image)) {
            File::delete(public_path('uploads/category/thumb/' . $category->image));
            File::delete(public_path('uploads/category/' . $category->image));
        }

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully',
        ]);
    }
}
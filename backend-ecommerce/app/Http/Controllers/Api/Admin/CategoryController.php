<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get categories
        $categories = Category::when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'like', '%' .
                request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new CategoryResource(
            true,
            'List Data Categories',
            $categories
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|unique:categories',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());
        //create category
        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);
        if ($category) {
            //return success with Api Resource
            return new CategoryResource(true, 'Data Saved Successfully!', $category);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Failed to Save!', null);
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::whereId($id)->first();
        if ($category) {
            //return success with Api Resource
            return new CategoryResource(
                true,
                'Details Data Category!',
                $category
            );
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Category Data Details Not Found!', null);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' =>
            'required|unique:categories,name,' . $category->id,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update
        if ($request->file('image')) {
            //remove old image
            Storage::disk('local')->delete('public/categories/' . basename($category->image));
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());
            //update category with new image
            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        }
        //update category without image
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);
        if ($category) {
            //return success with Api Resource
            return new CategoryResource(true, 'Data Updated Successfully!', $category);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Failed to Update!', null);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //remove image
        Storage::disk('local')->delete('public/categories/' . basename($category->image));
        if ($category->delete()) {
            //return success with Api Resource
            return new CategoryResource(true, 'Data Deleted Successfully!', null);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Failed to Delete!', null);
    }
}

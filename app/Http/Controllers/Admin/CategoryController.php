<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Category List';
        $categories = Category::latest()->get();
        return view('admin.category.index',compact('pageTitle', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Category Create';
        return view('admin.category.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' =>'required',
            'meta_title'   =>'required',
            'keywords'     =>'required',
            'meta_description' =>'required',
            'type' =>'required',
            'status'=>'required',
        ]);


        $user_id = Auth::guard('admin')->user()->id;
        Category::create([
            'name' => $request->name,
            'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->name))),
            'type'       => $request->type,
            'meta_title'  => $request->meta_title,
            'keywords'     => implode(',', $request->keywords),
            'meta_description'=> $request->meta_description,
            'status' => $request->status,
            'created_by'        => $user_id,
        ]);

        flash()->addSuccess("Category Created Successfully.");
        $url = '/admin/categories/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Category View';
        $category = Category::find($id);
        return view('admin.category.show',compact('pageTitle','category'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Category Edit';
        $category = Category::find($id);
        return view('admin.category.edit',compact('pageTitle','category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $this->validate($request, [
        //     'name' =>'required',
        //    'status'=>'required',
        // ]);

        $user_id = Auth::guard('admin')->user()->id;

        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->name)));
        $category->meta_title = $request->meta_title;
        $category->type = $request->type;
        $category->keywords = implode(',', $request->keywords);
        $category->meta_description = $request->meta_description;
        $category->status = $request->status;
        $category->save();

        flash()->addSuccess("Category Updated Successfully.");
        $url = '/admin/categories/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();

        flash()->addError("Category Deleted Successfully.");
        $url = '/admin/categories/index';
        return redirect($url);
    }
}

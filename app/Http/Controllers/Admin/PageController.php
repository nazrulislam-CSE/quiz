<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Auth;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Page List';
        $pages = Page::latest()->get();
        return view('admin.page.index',compact('pageTitle', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Page Create';
        return view('admin.page.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'page_title'        =>'required',
            'page_name'         =>'required',
            'meta_title'        =>'required',
            'keywords'          =>'required',
            'meta_description'  =>'required',
            'page_description'  =>'required',
            // 'position'          =>'required',
            // 'is_default'        =>'required',
            'status'            =>'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $user_id = Auth::guard('admin')->user()->id;

        
        $page = Page::create([
            'page_title'        => $request->page_title,
            'page_name'         => $request->page_name,
            'page_slug'         => strtolower(trim(preg_replace('/\s+/', '-', $request->page_name))),
            'meta_title'        => $request->meta_title,
            'keywords'          => implode(',', $request->keywords),
            'meta_description'  => $request->meta_description,
            'page_description'  => $request->page_description,
            'is_default'        => $request->is_default,
            'position'          => $request->position,
            'status'            => $request->status,
            'created_by'        => $user_id,
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/page/'.$page->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/page'),$filename);
            $page['image'] = $filename;
        }

        $page->save();

        flash()->addSuccess("Page Created Successfully.");
        $url = '/admin/pages/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Page Show';
        $page = Page::find($id);
        return view('admin.page.show',compact('pageTitle','page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Page Edit';
        $page = Page::find($id);
        return view('admin.page.edit',compact('pageTitle','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_id = Auth::guard('admin')->user()->id;

        $page = Page::find($id);
        $page->page_title = $request->page_title;
        $page->page_name = $request->page_name;
        $page->page_slug = strtolower(trim(preg_replace('/\s+/', '-', $request->page_name)));
        $page->meta_title = $request->meta_title;
        $page->keywords = implode(',', $request->keywords);
        $page->meta_description = $request->meta_description;
        $page->page_description = $request->page_description;
        $page->is_default = $request->is_default;
        $page->position = $request->position;
        $page->status = $request->status;
        $page->created_by = $user_id;
        $page->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/page/'.$page->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/page'),$filename);
            $page['image'] = $filename;
        }

        $page->save();

        flash()->addSuccess("Page Updated Successfully.");
        $url = '/admin/pages/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::find($id);

        try {
            if(file_exists($page->image)){
                unlink($page->image);
            }
        } catch (Exception $e) {

        }

        $page->delete();
        

        flash()->addSuccess("Page Deleted Successfully.");
        $url = '/admin/pages/index';
        return redirect($url);
    }
}

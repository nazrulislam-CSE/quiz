<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Menuitem;
use App\Models\Category;
use App\Models\Page;
use Session;

class MenuBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $desiredMenu = '';  
        $data['menus'] = Menu::where('type', 'megaMenu')->get();
        if($request->menu && $request->menu != 'new'){

          $desiredMenu = Menu::where('id', $request->menu)->first();
          $data['menuitems'] = Menuitem::with('subMenus.childMenus')->whereNull('parent_id')->where('menu_id',$desiredMenu->id)->orderby('position', 'asc')->get();    
          
        }else{
          $data['menuitems'] = [];
        }
    
    
        $data['categories'] = Category::with('subcategory')->where('parent_id', null)->where('status',1)->get();
        $data['pages'] = Page::where('status', 1)->get();
       
        $data['desiredMenu'] = $desiredMenu;
        return view('admin.menu.menu-builder')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $menu = new Menu();

        $menu->name = $request->name;
        $menu->slug = strtolower(trim(preg_replace('/\s+/', '-', $request->name)));
        $menu->type = 'megaMenu';
        $menu->save();
        $menu->save();
        
        if($menu){ 
            flash()->addSuccess("Menu Created Successfully.");  
            $url = route('admin.menuBuilder')."?menu=".$menu->id;        
            return redirect($url);
        }else{
            flash()->addError("Failed to save menu !.");
            return redirect()->back();
        }
    }

    public function addItemToMenu(Request $request){

        $menuid = $request->menuid;
        $ids = $request->ids;
        $menu = Menu::findOrFail($menuid);
        if($menu){
          if($request->sourch == 'category'){
            if($request->ids && count($request->ids)>0){
              foreach($ids as $id){
                $category = Category::where('id',$id)->first();
                $menuItem = new Menuitem();
                $menuItem->title = $category->name;
                $menuItem->url = 'category/'.$category->slug;
                $menuItem->sourch = 'category';
                $menuItem->parent_id = null;
                $menuItem->location = $menu->location;
                $menuItem->menu_id = $menuid;
                $menuItem->category_id = $category->id;
                $menuItem->save();
              }
            }
            if($request->subids && count($request->subids)>0){
            
              foreach($request->subids as $id){
                $category = Category::where('id',$id)->first();
                $menuItem = new Menuitem();
                $menuItem->title = $category->category_bd;
                $menuItem->url = 'category/'.$category->slug;
                $menuItem->sourch = 'category';
                $menuItem->parent_id = null;
                $menuItem->location = $menu->location;
                $menuItem->menu_id = $menuid;
                $menuItem->category_id = $category->id;
                $menuItem->save();
              }
            }
    
            if($request->childids && count($request->childids)>0){
            
              foreach($request->childids as $id){
                $category = Category::where('id',$id)->first();
                $menuItem = new Menuitem();
                $menuItem->title = $category->category_bd;
                $menuItem->url = 'category/'.$category->slug;
                $menuItem->sourch = 'category';
                $menuItem->parent_id = null;
                $menuItem->location = $menu->location;
                $menuItem->menu_id = $menuid;
                $menuItem->category_id = $category->id;
                $menuItem->save();
              }
            }
          
          }elseif($request->sourch == 'page'){
            foreach($ids as $id){
              $page = Page::where('id',$id)->first();
              $menuItem = new Menuitem();
              $menuItem->title = $page->page_name;
              $menuItem->url = $page->page_slug;
              $menuItem->sourch = 'page';
              $menuItem->parent_id = null;
              $menuItem->location = $menu->location;
              $menuItem->page_id = $page->id;
              $menuItem->menu_id = $menuid;
              $menuItem->save();
            }
          }
          elseif($request->sourch == 'banner'){
              foreach($ids as $id){
                $banner = Banner::where('id',$id)->first();
                $menuItem = new Menuitem();
                $menuItem->title = $banner->title;
                $menuItem->url = $banner->id;
                $menuItem->sourch = 'banner';
                $menuItem->parent_id = null;
                $menuItem->location = $menu->location;
                $menuItem->menu_id = $menuid;
                $menuItem->save();
              }
          }else{
              $menuItem = new Menuitem();
              $menuItem->title = $request->link;
              $menuItem->url = $request->url;
              $menuItem->sourch = 'custom';
              $menuItem->parent_id = null;
              $menuItem->location = $menu->location;
              $menuItem->menu_id = $menuid;
              $menuItem->save();
            }
        }else{
            flash()->addError("Menu not found!.");
            return redirect()->route('menuBuilder');
        }
        flash()->addSuccess("Menu Updated Successfully.");  
        return redirect()->back();
    }

    public function createMenu(Request $request){
        $menu = Menu::find($request->menuid);
        $menu->location = $request->location;
        $menu->save();

        flash()->addSuccess("Set Menu Created Location Successfully.");
    }

    public function updateMenu(Request $request){

        $menu = Menu::find($request->menuid);
        $menu->location = $request->location;
        $menu->save();

        flash()->addSuccess("Set Menu Location Updated Successfully.");  
        $menuItemOrder = json_decode($request->input('itemids'));
        $this->orderMenu($menuItemOrder, null); 
      }
    
      private function orderMenu(array $menuItems, $parentId)
      {
        foreach ($menuItems as $index => $menuItem) {
            $item = MenuItem::findOrFail($menuItem->id);
            $item->position = $index + 1;
            $item->parent_id = $parentId;
            $item->save();
            //if set child re-call function
            if(isset($menuItem->children)) {
              $this->orderMenu($menuItem->children, $item->id);
            }
        }
      }


      public function updateMenuItem(Request $request, $id){
        $item = Menuitem::find($id);
        $item->title = $request->title;
        $item->title_hidden = ($request->title_hidden) ? 1 : null;
        $item->url = ($request->url) ? $request->url : $item->url ;
        // $item->menu_type = ($request->menu_type) ? $request->menu_type : null;
        // $item->menu_width = ($request->menu_width) ? $request->menu_width : null;
        $item->sub_title = strtolower(trim(preg_replace('/\s+/', '-', $request->title)));
        $item->save();

        flash()->addSuccess("Set Menu Item Updated Successfully."); 
        return redirect()->back();
      }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function deleteMenuItem($id){        
        $menuitem = Menuitem::where('id',$id)->delete();
        if($menuitem ){
        $output = [
                'status' => true,
                'msg' => 'Item deleted successfully.'
            ];
        }else{
            $output = [
                'status' => false,
                'msg' => 'Item cannot deleted.'
            ];
          }
        return response()->json($output);
      } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Menuitem::where('menu_id',$id)->delete();  
      Menu::findOrFail($id)->delete();
      flash()->addSuccess("Menu Permanently Deleted Successfully.");
      return redirect()->route('menuBuilder');
    }
}

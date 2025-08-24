
@extends('layouts.admin.app')
@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('dashboard/node_modules/nestable.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
#main-wrapper{overflow: visible !important;}

.panel-default{padding:0px; border-radius: 3px;    border: 1px solid #e1e1e1;  background: #f1f1f1; margin-bottom: 10px; list-style: none;}
.item-list-body{background: #fff;padding: 5px 0 5px 10px;}
.action_btn{ margin-top: 5px;}
.deactive_module{background-color: #e8dada9c;}
.panel-title>a, .panel-title>a:active{ display:block;padding:12px 0;color:#555;font-size:14px;font-weight:bold;}

.pull-right{float: right;padding-right: 5px;}

.panel-heading a { display: block; padding: 5px; color: #666666;font-weight: 600;}
.item-list-footer{padding: 5px 15px 5px 0;}
.item-list-body label{width: 100%;}
.item-list-body{max-height: 300px;overflow-y: scroll;}
.panel-body p{margin-bottom: 5px;}

.form-inline{display: inline;}
.form-inline select{padding: 4px 10px;}
.disabled{pointer-events: none; opacity: 0.5;}
.menulocation label{font-weight: normal;display: block; padding-left: 10px;}
.input-box{background: #f9f9f9;padding: 5px 15px;box-sizing: border-box;margin-top: -5px;border: 1px solid #f9f9f9;}
.input-box .form-group{width: 90%; position: relative;}
.adjust-field{border: none;border-radius:0;position: absolute;right: 0;background: #e9ecef;padding: 9px 5px;}
.dd3-handle:before {
    color: #67757c;
    top: 7px;
}
/*========== drag & drop font css ==========*/
.dd{
    width: 100%;
}
.dd3-handle:before {
    content: "\f047";
    font-family: 'FontAwesome';
    font-weight: 900;
    display: block;
    position: absolute;
    left: 0;
    top: 3px;
    width: 100%;
    text-align: center;
    text-indent: 0;
    color:#46c37b;
    font-size: 14px;
}
#pageLoading{
    z-index: 999999; 
    width: 100%;
    height: 50%;
    display: none;
    left: 0;
    min-height: 50px;
    position: fixed;
    background: url('{{ asset("dashboard/img/loading.gif")}}') no-repeat center; 
}
.collapse_angle {
    background: #46c37b;
    color: #fff;
    border: none;
}
.dd3-item>button {
    color: red;
}
</style>  
@endpush
<div class="breadcrumb-header justify-content-between">
  <div class="d-flex align-items-center">
      {{-- <h4 class="content-title mb-2">Hi, welcome back!</h4> --}}
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
              <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
          </ol>
      </nav>
  </div>
  <div class="d-flex my-auto">
      <div class=" d-flex right-page">
          <div class="d-flex justify-content-center me-5">
              <div class="">
                  <span class="d-block">
                      <span class="label ">EXPENSES</span>
                  </span>
                  <span class="value">
                      $53,000
                  </span>
              </div>
              <div class="ms-3 mt-2">
                  <span class="sparkline_bar"></span>
              </div>
          </div>
          <div class="d-flex justify-content-center">
              <div class="">
                  <span class="d-block">
                      <span class="label">PROFIT</span>
                  </span>
                  <span class="value">
                      $34,000
                  </span>
              </div>
              <div class="ms-3 mt-2">
                  <span class="sparkline_bar31"></span>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="main-content-body">
      <!-- Row -->
      <div class="row row-sm">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                      <p class="card-title my-0">Menu Builder Create <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($categories) }}</span> </p>

                      <div class="d-flex">
                          <a href="{{ route('admin.category.create')}}" class="btn btn-success me-2">
                              <i class="fas fa-list d-inline"></i> Dashboard
                          </a>
                      </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-10">
                         <div class="form-group">
                            <label for="name">Select A Menu:</label>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if(count($menus) > 0)    
                            <form action="{{route('admin.menuBuilder')}}" method="get" class="form-inline">
                            <div class="input-group mb-3">
                               <select required name="menu" class="form-control custom-select">
                                   <option value="">Select menu</option>
                                 @foreach($menus as $menu)
                                   <option @if(Request::get('menu') == $menu->id) selected @endif value="{{$menu->id}}">{{$menu->name}}</option>
                                 @endforeach
                               </select>
                               <button class="btn btn-primary text-light">  <i class="si si-plus"></i> Select</button>
                            </div>
                           </form> 
                           @endif
                         </div>
                      </div>
                      <div class="col-md-2">
                         <label for="name"></label>
                         <div class="form-group">
                            <a href="{{route('admin.menuBuilder')}}?menu=new" class="btn btn-success text-light mt-2">
                               <i class="si si-plus"></i> Create a new menu
                            </a>
                         </div>
                      </div>
                   </div>
              </div>
          </div>
      </div>
      <!-- End Row -->
      <div class="row row-sm">
        <div id="pageLoading"></div>
        <div class="col-lg-4 col-md-12">
          <div class="card overflow-hidden @if(count($menus) == 0 || !$desiredMenu ) disabled @endif">
            <div class="card-header pb-0">
              <h3 class="card-title">Add Menu Items</h3>
            </div>
            <div class="card-body">
              {{-- start categories section  --}}
              {{-- <div class="panel-group1" id="accordion11">
                <div class="panel panel-default  mb-4">
                  <div class="panel-heading1 bg-success">
                    <h4 class="panel-title1">
                      <a class="accordion-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-parent="#accordion11" href="#collapseFour1"
                        aria-expanded="false"><i class="fe fe-arrow-right me-2"></i>Categories
                        </a>
                    </h4>
                  </div>
                  <div id="collapseFour1" class="panel-collapse collapse show" role="tabpanel"
                    aria-expanded="false">
                    <div class="panel-body border">
                      <div class="item-list-body">
                        <input id="categoriesList" class="form-control mb-3 mt-3" type="search" placeholder="Search Categories" aria-label="Search">
                          <div class="col-12 categoriesList" id="categories-list">
                            @foreach($categories as $cat)                                                
                            <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1">
                                <input type="checkbox" class="custom-control-input" id="{{$cat->id}}" name="select-category[]" value="{{$cat->id}}">
                                <label class="custom-control-label" for="{{ $cat->id }}" style="cursor: pointer;">{{$cat->name}}</label>
                            </div>
                            @foreach($cat->subcategory as $subcategory)
                                <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1">
                                    <input type="checkbox" class="custom-control-input" id="{{$subcategory->id}}" name="select-subcategory[]" value="{{$subcategory->id}}">
                                    <label class="custom-control-label" for="{{ $subcategory->id }}" style="cursor: pointer;">{{$subcategory->name}}</label>
                                </div>
                                @foreach($subcategory->subcategory as $childcategory)
                                    <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1">
                                        <input type="checkbox" class="custom-control-input" id="{{$childcategory->id}}" name="select-childcategory[]" value="{{$childcategory->id}}">
                                        <label class="custom-control-label" for="{{ $childcategory->id }}" style="cursor: pointer;">{{$childcategory->name}}</label>
                                    </div>
                                @endforeach
                            @endforeach
                        @endforeach
                          </div>
                          <hr>
                        <div class="col-12 mt-2">
                            <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1 ">
                              <input type="checkbox" class="custom-control-input" id="select-all-categories" name="example-cb-custom-success-lg1" value="all">
                              <label class="custom-control-label" for="select-all-categories" style="cursor: pointer;">Select All</label><br>
                              <strong id="categories" class="text-danger font-weaight-bolder"></strong>
                              
                              <button class="btn btn-success btn-sm" id="add-categories" style=" float:right;">
                                  <i class="si si-plus"></i> Add
                              </button>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> --}}
              {{-- end categories section  --}}

              {{-- start pages section  --}}
              <div class="panel-group1" id="accordion11">
                <div class="panel panel-default  mb-4">
                  <div class="panel-heading1 bg-success">
                    <h4 class="panel-title1">
                      <a class="accordion-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-parent="#accordion11" href="#collapseFour2"
                        aria-expanded="false"><i class="fe fe-arrow-right me-2"></i>Pages
                        </a>
                    </h4>
                  </div>
                  <div id="collapseFour2" class="panel-collapse collapse" role="tabpanel"
                    aria-expanded="false">
                    <div class="panel-body border">
                      <div class="item-list-body">
                          <div class="col-12" id="pages-list">
                            @foreach($pages as $key=> $page)                                              
                              <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1">
                                  <input type="checkbox" class="custom-control-input" id="{{ $page->id }}" name="select-page[]" value="{{$page->id}}">
                                  <label class="custom-control-label" for="{{ $page->id }}" style="cursor: pointer;">{{$page->page_name}}</label>
                              </div>
                            @endforeach
                          </div>
                          <hr>
                        <div class="col-12 mt-2">
                            <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1 ">
                              <input type="checkbox" class="custom-control-input" id="select-all-pages" name="example-cb-custom-success-lg1" value="all">
                              <label class="custom-control-label" for="select-all-pages" style="cursor: pointer;">Select All</label>
                              <strong id="pages" class="text-danger font-weaight-bolder"></strong>
                              <button class="btn btn-success btn-sm" id="add-pages" style=" float:right;">
                                  <i class="si si-plus"></i> Add
                              </button>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- start pages section  --}}

              {{-- start custom  section  --}}
              <div class="panel-group1" id="accordion11">
                <div class="panel panel-default  mb-4">
                  <div class="panel-heading1 bg-success">
                    <h4 class="panel-title1">
                      <a class="accordion-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-parent="#accordion11" href="#collapseFour3"
                        aria-expanded="false"><i class="fe fe-arrow-right me-2"></i>  Custom Links
                        </a>
                    </h4>
                  </div>
                  <div id="collapseFour3" class="panel-collapse collapse" role="tabpanel"
                    aria-expanded="false">
                    <div class="panel-body border">
                      <div class="item-list-body">
                          <div class="col-12" id="custom-list">
                            <div class="form-group">
                              <label>URL  <strong id="url_required" class="text-danger font-weaight-bolder"></strong></label>
                              <input type="url" name="url" id="url" class="form-control" placeholder="https://">
                           </div>
                           <div class="form-group">
                              <label>Link Text <strong id="linktext_required" class="text-danger font-weaight-bolder"></strong></label>
                              <input type="text" id="linktext" class="form-control" placeholder="Enter link text">
                           </div>
                          </div>
                          <hr>
                        <div class="col-12 mt-2">
                          <div class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1 ">
                            <strong id="customLink" class="text-danger font-weaight-bolder"></strong>
                            <button class="btn btn-success btn-sm" id="add-custom-link" style=" float:right;">
                               <i class="si si-plus"></i> Add
                            </button>
                         </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end custom  section    --}}

            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="col-lg-12 col-md-12">
						<div class="card overflow-hidden">
							<div class="card-header pb-0">
								<h3 class="card-title">Menu Structure</h3>
							</div>
							<div class="card-body">
								<div class="panel-group1" id="accordion11">
									<div class="mb-4">
                    @if(Request::get('menu') == 'new') 
                      <h5 class="mb-2">Create New Menu</h5><hr>
                      <form method="post" action="{{route('admin.menu.store')}}" class="mt-2">
                        @csrf
                        <div class="row">
                          <div class="form-group col-xl-12 col-lg-12 col-md-6">
                            <label for="name">Menu Name: <span class="text-danger"></span></label>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                              <div class="input-group">
                                 <span class="input-group-text" title="Menu Name" id="basic-addon1"><i class="fas fa-tags" title="Menu Name"></i></span>
                                 <input type="text" value="" class=" form-control" name="name" placeholder="Enter New Menu Name" required>
                             </div>
                         </div>

                        <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                          <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-plus"></i> Create New Menu</button>
                        </div>
                        </div>
                      </form>
                    @else
                      @if ($desiredMenu)    
                        @if(count($menuitems)>0)
                          <div class="row">
                            <h4 class="pl-3">Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</h4>
                            <h4 class="font-weight-bolder mt-2 mb-3 pl-3 text-success">Menu Name:
                                <span class="text-danger">{{ $desiredMenu->name ?? 'Null' }}</span>
                                <button class="btn btn-danger pull-right" id="delete" href="{{route('admin.deleteMenu',$desiredMenu->id)}}"> <i class="fas fa-trash"></i></button>
                            </h4>
                            <div class="col-sm-12">
                              <div class="myadmin-dd-empty dd" id="nestable2">
                                <ol class="dd-list">
                                  @foreach($menuitems as $menuitem)
                                  <li class="dd-item dd3-item" id="item{{$menuitem->id}}" data-id="{{$menuitem->id}}">
                                      <div class="dd-handle dd3-handle"></div>
                                      <div class="dd3-content shadow">
                                          {{$menuitem->title}}
                                          <button href="#collapse{{$menuitem->id}}" class="pull-right collapse_angle" data-bs-toggle="collapse">
                                              <i class="fa fa-angle-down" style="cursor: pointer;"></i>
                                          </button>
                                      </div>
                                      <div class="collapse" id="collapse{{$menuitem->id}}">
                                        <div class="card shadow-lg p-3">
                                          <div class="card-header"></div>
                                          <div class="card-body">
                                            <form method="post" action="{{route('admin.updateMenuItem', $menuitem->id)}}">
                                              @csrf
                                              <div class="row">
                                                <div class="col-sm-6">
                                                  <div class="form-group">
                                                      <label for="title">Title</label>
                                                      <input type="text" name="title" value="{{$menuitem->title}}" id="title" class="form-control" placeholder="Enter title">
                                                  </div>
                                                </div>
                                                <div class="col-sm-6">
                                                  <div class="form-group">
                                                      <label for="title">Sub Title</label>
                                                      <input type="text" name="sub_title" value="{{$menuitem->sub_title}}" id="sub_title" class="form-control" placeholder="Enter sub title" readonly>
                                                  </div>
                                                </div>
                                                <div class="col-sm-6">
                                                  <label for="menu_type">Menu Type</label>
                                                  <select name="menu_type" class="form-control" id="menu_type">
                                                    <label>Menu Type </label>
                                                    <option @if($menuitem->menu_type == 'nav-item') selected @endif value="nav-item"> Classic Menu</option>
                                                    <option @if($menuitem->menu_type == 'has_dropdown has_mega_menu') selected @endif value="has_dropdown has_mega_menu"> Mega Menu</option>
                                                    <option @if($menuitem->menu_type == 'nav-item has_dropdown has_mega_menu') selected @endif value="nav-item has_dropdown has_mega_menu"> Mega half</option>
                                                  </select>
                                                </div>
                                                @if($menuitem->sourch == 'custom')
                                                <div class="col-sm-6">
                                                  <div class="form-group">
                                                      <label for="url">URL</label>
                                                      <input type="text" name="url" id="url" placeholder="Exp: {{url('url')}}" value="{{$menuitem->url}}" class="form-control">
                                                  </div>
                                                </div>
                                                @endif
                                                <div class="col-sm-12 mt-3 text-right">
                                                  <button class="btn btn-success"><i class="fas fa-plus"></i> Update</button>
                                                </div>
                                              </div>
                                           </form>
                                           <button href="javascript:void(0)" onclick="deleteMenuItem({{$menuitem->id}})" class="btn btn-danger" style="float: right;"><i class="fas fa-trash"></i> Delete</button>
                                          </div>
                                        </div>
                                      </div>
                                      @if(count($menuitem->subMenus)>0)
                                      <ol class="dd-list" style="list-style-type: none !important;">
                                          @foreach($menuitem->subMenus as $subMenu)
                                           <li class="dd-item dd3-item" id="item{{$subMenu->id}}" data-id="{{$subMenu->id}}">
                                              <div class="dd-handle dd3-handle"></div>
                                              <div class="dd3-content">
                                                  {{$subMenu->title}}
                                                  <button href="#collapse{{$subMenu->id}}" class="pull-right collapse_angle" data-bs-toggle="collapse">
                                                      <i class="fa fa-angle-down" style="cursor: pointer;"></i>
                                                  </button>
                                              </div>
                                              <div class="collapse" id="collapse{{$subMenu->id}}">
                                                <div class="card shadow-lg p-3">
                                                  <div class="card-header"></div>
                                                  <div class="card-body">
                                                    <form method="post" action="{{route('admin.updateMenuItem', $subMenu->id)}}">
                                                      @csrf
                                                      <div class="row">
                                                        <div class="col-sm-6">
                                                          <div class="form-group">
                                                              <label for="title">Title</label>
                                                              <input type="text" name="title" value="{{$subMenu->title}}" id="title" class="form-control" placeholder="Enter title">
                                                          </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                          <div class="form-group">
                                                              <label for="title">Sub Title</label>
                                                              <input type="text" name="sub_title" value="{{$subMenu->sub_title}}" id="sub_title" class="form-control" placeholder="Enter sub title" readonly>
                                                          </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                          <label for="menu_type">Menu Type</label>
                                                          <select name="menu_type" class=" form-control">
                                                            <option value="">Select Menu Type</option>
                                                                <option @if($menuitem->menu_type == 'left') selected @endif value="left"> Left Menu</option>
                                                                <option @if($menuitem->menu_type == 'right') selected @endif value="right"> Right Menu</option>
                                                            </select>
                                                        </div>
                                                        @if($subMenu->sourch == 'custom')
                                                        <div class="col-sm-6">
                                                          <div class="form-group">
                                                              <label for="url">URL</label>
                                                              <input type="text" name="url" id="url" placeholder="Exp: {{url('url')}}" value="{{$subMenu->url}}" class="form-control">
                                                          </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-sm-12 mt-3">
                                                          <button class="btn btn-success"><i class="fas fa-plus"></i> Update</button>
                                                        </div>
                                                      </div>
                                                   </form>
                                                   <button href="javascript:void(0)" onclick="deleteMenuItem({{$subMenu->id}})" class="btn btn-danger" style="float: right"><i class="fas fa-trash"></i> Delete</button>
                                                  </div>
                                                </div>
                                              </div>
                                              @if(count($subMenu->subMenus)>0)
                                              <ol class="dd-list">
                                                  @foreach($subMenu->subMenus as $childMenu)
                                                      <li class="dd-item dd3-item" id="item{{$childMenu->id}}" data-id="{{$childMenu->id}}">
                                                          <div class="dd-handle dd3-handle"></div>
                                                          <div class="dd3-content">
                                                              {{$childMenu->title}}
                                                              <button href="#collapse{{$childMenu->id}}" class="pull-right collapse_angle" data-bs-toggle="collapse">
                                                                  <i class="fa fa-angle-down"></i>
                                                              </button>
                                                          </div>
                                                          <div class="collapse" id="collapse{{$childMenu->id}}">
                                                            <div class="card shadow-lg p-3">
                                                              <div class="card-header"></div>
                                                              <div class="card-body">
                                                                <form method="post" action="{{route('admin.updateMenuItem', $childMenu->id)}}">
                                                                  @csrf
                                                                  <div class="row">
                                                                    <div class="col-sm-6">
                                                                      <div class="form-group">
                                                                          <label for="title">Title</label>
                                                                          <input type="text" name="title" value="{{$childMenu->title}}" id="title" class="form-control" placeholder="Enter title">
                                                                      </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                      <div class="form-group">
                                                                          <label for="title">Sub Title</label>
                                                                          <input type="text" name="sub_title" value="{{$childMenu->sub_title}}" id="sub_title" class="form-control" placeholder="Enter sub title" readonly>
                                                                      </div>
                                                                    </div>
                                                                    @if($childMenu->sourch == 'custom')
                                                                    <div class="col-sm-6">
                                                                      <div class="form-group">
                                                                          <label for="url">URL</label>
                                                                          <input type="text" name="url" id="url" placeholder="Exp: {{url('url')}}" value="{{$childMenu->url}}" class="form-control">
                                                                      </div>
                                                                    </div>
                                                                    @endif
                                                                    <div class="col-sm-12 mt-3">
                                                                      <button class="btn btn-success"><i class="fas fa-plus"></i> Update</button>
                                                                    </div>
                                                                  </div>
                                                               </form>
                                                               <button href="javascript:void(0)" onclick="deleteMenuItem({{$childMenu->id}})" class="btn btn-danger" style="float: right;"><i class="fas fa-trash"></i> Delete</button>
                                                              </div>
                                                            </div>
                                                          </div>
                                                      </li>
                                                  @endforeach
                                              </ol>
                                              @endif
                                          </li>
                                          @endforeach
                                      </ol>
                                      @endif
                                  </li>
                              @endforeach
                                </ol>
                            </div>
                            </div>
                          </div>
                        @else
                          <h5>Menu item not found, please add menu items from the column on the left.</h5>
                          <h4 class="font-weight-bolder  text-success">Menu Name:
                              <span class="text-danger">{{ $desiredMenu->name ?? 'Null' }}</span>
                          </h4>
                          
                          <h3 class="font-weight-bolder text-success mt-2 mb-2">Menu Location:</h3><hr>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg2"  name="location"  value="top_header" @if($desiredMenu->location == 'top_header') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg2" style="cursor: pointer;">Top Left</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg3"  name="location"  value="top_header2" @if($desiredMenu->location == 'top_header2') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg3" style="cursor: pointer;">Top Right</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg4"  name="location"  value="main_header" @if($desiredMenu->location == 'main_header') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg4" style="cursor: pointer;">Main Navigation</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg5"  name="location"  value="main_header1" @if($desiredMenu->location == 'main_header1') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg5" style="cursor: pointer;">Main Left</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg6"  name="location"  value="main_header2" @if($desiredMenu->location == 'main_header2') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg6" style="cursor: pointer;">Main Right</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg7"  name="location"  value="footer1" @if($desiredMenu->location == 'footer1') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg7" style="cursor: pointer;">Footer 1</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg8"  name="location"  value="footer2" @if($desiredMenu->location == 'footer2') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg8" style="cursor: pointer;">Footer 2</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-success custom-control-lg mb-1">
                              <input type="radio" class="custom-control-input" id="example-rd-custom-success-lg9"  name="location"  value="footer3" @if($desiredMenu->location == 'footer3') checked @endif required="">
                              <label class="custom-control-label" for="example-rd-custom-success-lg9" style="cursor: pointer;">Footer 3</label>
                          </div>

                          <strong id="location" class="text-danger font-weaight-bolder"></strong>
                            
                          <div class="mt-3">
                              <button href="#" class="btn btn-danger">
                                  <i class="far fa-window-close"></i>
                                      Delete Menu
                              </button>
                              <button id="createMenu"  class="btn btn-success" style="float: right;">
                                  <i class="fas fa-plus"></i> Set Menu Location
                              </button>
                          </div>
                        @endif
                      @else
                        <h5>Please create a new menu or select the menu from the top left.</h5>
                      @endif
                    @endif
                  </div>
								</div>
							</div>
						</div>
					</div>
        </div>
      </div>
</div>
 @push('admin')
     <!--Nestable js -->
     <script src="{{ asset('dashboard/node_modules/jquery.nestable.js')}}"></script>
     @if($desiredMenu)
        <script>
        $(function(){
          $('#categoriesList').keyup(function(){
            // alert('ok');
            var searchText = $(this).val().toUpperCase();

            $('.categoriesList div label').each(function(){
                var currentLiText = $(this).text(),
                    showCurrentLi = currentLiText.toUpperCase().indexOf(searchText) !== -1;
                $(this).toggle(showCurrentLi);
                });     
            });
          });
    
          $('.dd').nestable({maxDepth: 3});
       
          $('#createMenu').click(function(){
            var menuid = <?=$desiredMenu->id?>;
            // alert(menuid);
            var location = $('input[name="location"]:checked').val();
            // alert(location);
            if(!location){
              $('#location').html('Please select location.');
              return false
            }
    
            $.ajax({
              type:"get",
              data: {menuid:menuid,location:location},
              url: "{{route('admin.createMenu')}}",              
              success:function(res){
                window.location.reload();
              }
            })    
          });
    
          //save data on drag
          $('.dd').on('change', function (e) {
    
            var menuid = <?=$desiredMenu->id?>;
            var location = $('input[name="location"]:checked').val();
            if(!location){
              location = 'main_header'
            }
            var itemids = JSON.stringify($('.dd').nestable('serialize'));
    
            $.ajax({
              type:"get",
              data: {menuid:menuid,itemids:itemids,location:location},
              url: "{{route('admin.updateMenu')}}",              
              success:function(res){
                // console.log(res);
                // alert('Update Menu');
                Swal.fire({                    
                    toast: true,
                    position: 'top-end',
                    icon:'success',
                    title: 'Drag & Drop Menu Successfully Updated',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
              }
            }) 
          });
    
    
            function deleteMenuItem(id) {
              // alert(id);
                @if(env('MODE') == 'demo')
                  Swal.fire({                    
                      toast: true,
                      position: 'top-end',
                      icon:'error',
                      title: 'Demo mode on delete not working.',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                          toast.onmouseenter = Swal.stopTimer;
                          toast.onmouseleave = Swal.resumeTimer;
                      }
                  });
                  return false;
                @endif
                var route = '{{ route("admin.deleteMenuItem", ":id") }}';
                route = route.replace(":id", id);
                var item = $('#item').val();
                $.ajax({
                    url:route,
                    method:"get",
                    success:function(data){
                      console.log(data);
                      if(data.status){
                          $("#item"+id).remove();
                          Swal.fire({                    
                              toast: true,
                              position: 'top-end',
                              icon:'success',
                              title: 'Menu Item Deleted Successfully',
                              showConfirmButton: false,
                              timer: 3000,
                              timerProgressBar: true,
                              didOpen: (toast) => {
                                  toast.onmouseenter = Swal.stopTimer;
                                  toast.onmouseleave = Swal.resumeTimer;
                              }
                          });
                      }else{
                        Swal.fire({                    
                              toast: true,
                              position: 'top-end',
                              icon:'error',
                              title: 'Menu Item Doesnot Deleted.',
                              showConfirmButton: false,
                              timer: 3000,
                              timerProgressBar: true,
                              didOpen: (toast) => {
                                  toast.onmouseenter = Swal.stopTimer;
                                  toast.onmouseleave = Swal.resumeTimer;
                              }
                          });
                      }
                    }
                });
            }
        </script>
      
            <script>
              $('#select-all-categories').click(function(event) {
                if(this.checked) {
                  $('#categories-list :checkbox').each(function() {
                    this.checked = true;
                                         
                  });
                }else{
                  $('#categories-list :checkbox').each(function() {
                    this.checked = false;                        
                  });
                }
              });
            </script>
            <script>
              $('#select-all-pages').click(function(event) {  
                if(this.checked) {
                  $('#pages-list :checkbox').each(function() {
                    this.checked = true;                        
                  });
                }else{
                  $('#pages-list :checkbox').each(function() {
                    this.checked = false;                        
                  });
                }
              });
            </script>
            <script>
              $('#select-all-banners').click(function(event) {   
                if(this.checked) {
                  $('#banners-list :checkbox').each(function() {
                    this.checked = true;                        
                  });
                }else{
                  $('#banners-list :checkbox').each(function() {
                    this.checked = false;                        
                  });
                }
              });
            </script>
    
    <script>
      /* ========= Categories Added Now ========*/
      $('#add-categories').click(function(){

        var categories = $('input[name="select-category[]"]:checked').val();
        // alert(location);
        if(!categories){
          $('#categories').html('Please select Categories.');
          return false;
        }

        document.getElementById('pageLoading').style.display = 'block';

        var menuid = <?=$desiredMenu->id?>;
        var ids =  [];

        $.each($("input[name='select-category[]']:checked"), function(){            
          ids.push($(this).val());
        });
        var subids = [];
        $.each($("input[name='select-subcategory[]']:checked"), function(){            
          subids.push($(this).val());
        });
        var childids = [];
       
        $.each($("input[name='select-childcategory[]']:checked"), function(){            
          childids.push($(this).val());
        });
        if(ids.length == 0 && subids.length && childids.length == 0){
          return false;
        }
        $.ajax({
          type:"get",
          data: {menuid:menuid,ids:ids,subids:subids,childids:childids,sourch:'category'},
          url: "{{route('admin.addItemToMenu')}}",               
          success:function(res){  
            // alert('ok');            
            location.reload();
          }
        })
      });
    
      /* ========= Pages Added Now ========*/
      $('#add-pages').click(function(){
        
        var pages = $('input[name="select-page[]"]:checked').val();
        // alert(location);
        if(!pages){
          $('#pages').html('Please select Pages.');
          return false;
        }

        document.getElementById('pageLoading').style.display = 'block';

        var menuid = <?=$desiredMenu->id?>;
        var ids = [];
        $.each($("input[name='select-page[]']:checked"), function(){            
          ids.push($(this).val());
        });
        if(ids.length == 0){
          return false;
        }
        $.ajax({
          type:"get",
          data: {menuid:menuid,ids:ids,sourch:'page'},
          url: "{{route('admin.addItemToMenu')}}",               
          success:function(res){  
            // alert('ok');            
            location.reload();
          }
        })
      });

    /* ========= Custom Link Added Now ========*/
    $("#add-custom-link").click(function(){

      var menuid = <?=$desiredMenu->id?>;
      var url = $('#url').val();
      var link = $('#linktext').val();
      // Validate that both URL and link fields are not empty
      if (url.length === 0 || link.length === 0) {
          $('#url_required').html('Please Enter Url.');
          $('#linktext_required').html('Please Enter Linktext.');
          // alert("URL and Link Text are required.");
          return false;
      }

      document.getElementById('pageLoading').style.display = 'block';
        
       
        if(url.length > 0 && link.length > 0){
          $.ajax({
            type:"get",
            data: {menuid:menuid,url:url,link:link,sourch:'custom'},
            url: "{{route('admin.addItemToMenu')}}",                
            success:function(res){
              location.reload();
            }
          })
        }
      });
      
    
    </script>

    <script type="text/javascript">
        $(function(){
            $(document).on('click','#delete',function(e){
                e.preventDefault();
                var link = $(this).attr("href");

                Swal.fire({
                title: 'Are you sure menu permanently Deleted?',
                text: "Delete This Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                  window.location.href = link
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  )
                }
              })
            });
        });
    </script>
    @endif 
 @endpush
@endsection
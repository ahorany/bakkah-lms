<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a class="sidebar-close d-md-none" href="#"><i class="fas fa-times"></i></a>
  <!-- Brand Logo -->
  <a href="{{route('admin.home')}}" class="brand-link" style="border: none; padding-left: 26px;padding-right: 26px;background-color:#fb4400;">

    {{--<img src="{{CustomAsset('upload/logo_50.png')}}" alt="{{__('app.app_title')}}" title="{{__('app.app_title')}}" class="brand-image img-circle elevation-3">--}}
    <span class="brand-text font-weight-light">
      {{__('app.app_title')}}
    </span>
    <br>
    <small style="font-size: 0.7rem; display: block; padding-top: 5px;">{{auth()->user()->trans_name}}</small>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    {{--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
      @if(isset(auth()->user()->upload->file))
      <div class="image">
        <img src="{{CustomAsset('upload/thumb100_100/'.auth()->user()->upload->file)}}" class="img-circle elevation-2" alt="{{auth()->user()->trans_name}}" title="{{auth()->user()->trans_name}}">
      </div>
      @endif
      <div class="table">
        <a href="#" class="d-block">{{auth()->user()->trans_name}}</a>
      </div>
    </div>--}}

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        {{-- @foreach($user_pages as $aside) --}}
        @foreach($user_pages as $aside)
        <?php
          $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
          $active = ($aside->id==session('infastructure_parent_id')) ? 'active' : '';
          $menu_open = $active=='active'?'menu-open':'';
        ?>
        {{-- @if(isset(auth()->user()->roles)) --}}
        <li class="nav-item {{$has_treeview}} {{$menu_open}}"><!--menu-open-->

            {!!Builder::SidebarHref($aside, '#', $active)!!}

            @if($has_treeview=='has-treeview')

            <ul class="nav nav-treeview">
                @foreach($infastructures->where('parent_id', $aside->id) as $infa_child)
                {{-- @foreach($user_pages_child as $infa_child) --}}
                {{-- @if(auth()->user()->role->infrastructures->contains('id', $infa_child->id)) --}}
                {{-- @if ($infa_child->parent_id == $aside->id) --}}
                <li class="nav-item">
                    {!!Builder::SidebarHref($infa_child, null, '')!!}
                </li>
                {{-- @endif --}}
                {{-- @endif --}}
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

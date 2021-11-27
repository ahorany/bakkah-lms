<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a class="sidebar-close d-md-none" href="#"><i class="fas fa-times"></i></a>
  <!-- Brand Logo -->
  <a href="{{route('admin.home')}}" class="brand-link" style="padding-left: 26px;padding-right: 26px;background-color:#fb4400;">

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
        @if(auth()->user()->role_id==2)
              <li class="nav-item"><!--menu-open-->
                  <ul class="nav nav-treeview" style="display: inline-block;">
                      <li class="nav-item">
                          <a class="nav-link" href="{{route('training.carts.index', ['post_type'=>'cart'])}}">
                              <i class="nav-icon fas fa-chart-pie"></i>
                              Course Registration</a>
                      </li>
                  </ul>
              </li>
              <li class="nav-item"><!--menu-open-->
                  <ul class="nav nav-treeview" style="display: inline-block;">
                      <li class="nav-item">
                          <a class="nav-link" href="{{route('admin.contacts.index', ['post_type'=>'contact'])}}">
                              <i class="nav-icon fas fa-chart-pie"></i>
                              Contact Request</a>
                      </li>
                  </ul>
              </li>
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('training.carts.statistics', ['post_type'=>'statistics'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Statistics</a>
                    </li>
                </ul>
              </li>
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('training.carts.training-schedule', ['coin_id'=>334])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Training Schedule</a>
                    </li>
                </ul>
              </li>
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('evaluation.index', ['post_type'=>'evaluation'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Send Evaluation</a>
                    </li>
                </ul>
              </li>
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('training.interests.index', ['post_type'=>'interest'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Lead</a>
                    </li>
                </ul>
              </li>
              @if(auth()->user()->id==4904)
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('training.discounts.index', ['post_type'=>'discount'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Discount</a>
                    </li>
                </ul>
             </li>
             <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.partners.index', ['post_type'=>'clients'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Clients</a>
                    </li>
                </ul>
             </li>
             @endif

             @if(auth()->user()->id==8127)
             <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.partners.index', ['post_type'=>'clients'])}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Clients</a>
                    </li>
                </ul>
             </li>
             @endif

              @if(auth()->user()->id==5074 || auth()->user()->id==7084 || auth()->user()->id==7085)
              <li class="nav-item"><!--menu-open-->
                <ul class="nav nav-treeview" style="display: inline-block;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('xero.authorization')}}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            Sales Run</a>
                    </li>
                </ul>
             </li>
             @endif
        @else
            @foreach($asides as $aside)
            <?php
              $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
              $active = ($aside->id==session('infastructure_parent_id')) ? 'active' : '';
              $menu_open = $active=='active'?'menu-open':'';
            ?>
            {{-- @if(isset(auth()->user()->role->infrastructures)) --}}
                {{-- @if(auth()->user()->role->infrastructures->contains('id', $aside->id)) --}}
                <li class="nav-item {{$has_treeview}} {{$menu_open}}"><!--menu-open-->

                    {!!Builder::SidebarHref($aside, '#', $active)!!}

                    @if($has_treeview=='has-treeview')

                    <ul class="nav nav-treeview">
                        @foreach($infastructures->where('parent_id', $aside->id) as $infa_child)
                        {{-- @if(auth()->user()->role->infrastructures->contains('id', $infa_child->id)) --}}
                        <li class="nav-item">
                            {!!Builder::SidebarHref($infa_child, null, '')!!}
                        </li>
                        {{-- @endif --}}
                        @endforeach
                    </ul>

                    @endif
                </li>
                {{-- @endif --}}
            {{-- @endif --}}
            @endforeach
        @endif

        @if(auth()->user()->id==1 || auth()->user()->id==2)
        <li class="nav-item"><!--menu-open-->
        <ul class="nav nav-treeview" style="display: inline-block;">
            <li class="nav-item">
                <a class="nav-link" href="{{route('xero.authorization')}}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    Sales Run</a>
            </li>
        </ul>
        </li>
        @endif

        @if(auth()->user()->id== 4904 || auth()->user()->id== 2586 || auth()->user()->id== 8127)
        <li class="nav-item"><!--menu-open-->
            <ul class="nav nav-treeview" style="display: block;">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('crm::group_invoices.index', ['post_type'=>'rfq_invoices', 'type_id'=>373])}}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        RFQ Invoices</a>
                </li>
            </ul>
         </li>
         <li class="nav-item"><!--menu-open-->
            <ul class="nav nav-treeview" style="display: block;">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('crm::group_invoices.index', ['post_type'=>'b2b_invoices', 'type_id'=>370])}}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        B2B Invoices</a>
                </li>
            </ul>
         </li>
         @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

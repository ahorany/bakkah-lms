<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <?php
            $url = '';
            if(auth()->user()->upload) {
                // if ($url == ''){
                //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                // }else{
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
                // }
            }else {
                $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
            }
        ?>
        {{-- @if (file_exists($url)) --}}
        <div class="person-wrapper">
            <img src="{{$url}}" alt="">
            <h2 style="font-size: 1.2rem;">{{auth()->user()->trans_name}}</h2>
            <medium style="color: #73726c">{{auth()->user()->roles()->select('roles.name')->first()->trans_name??null}}</medium>
            <hr>
        </div>
        {{-- @endif --}}

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{url()->full() == CustomRoute('user.home')  ? 'active' : '' }}" aria-current="page" href="{{route('user.home')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Path_132" data-name="Path 132"
                              d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            @foreach($user_pages as $aside)
            <?php
              $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
              $active = ($aside->id==session('infastructure_parent_id')) && url()->full() != CustomRoute('user.home') ? 'active' : '';
              $menu_open = $active=='active'?'menu-open':'';
            ?>

            <li class="nav-item {{$has_treeview}} {{$menu_open}}"><!--menu-open-->

                {!!Builder::SidebarHref($aside, '#', $active)!!}

                @if($has_treeview=='has-treeview')
                    <ul class="nav-treeview">
                        @foreach($user_pages_child as $infa_child)
                            @if ($infa_child->parent_id == $aside->id)
                                <li class="nav-item">
                                    {!!Builder::SidebarHref($infa_child, null, '')!!}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>
            @endforeach

            @foreach($user_sidebar_courses->courses as $item)
                <li class="nav-item">
                    <a class="nav-link {{ (url()->full() == CustomRoute('user.course_details',$item->id)) && (url()->full() != CustomRoute('user.home'))  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40.86" height="39.254"
                             viewBox="0 0 40.86 39.254">
                            <path id="Path_131" data-name="Path 131"
                                  d="M40.428,11.8,20.848,22.039a.851.851,0,0,1-.787,0L.484,11.8A.854.854,0,0,1,.53,10.263L20.064.045a.855.855,0,0,1,.787,0l19.577,10.24a.855.855,0,0,1,0,1.513ZM20.457,1.764,2.719,11.043,20.454,20.32l17.739-9.278ZM.491,18.423,3.044,17.11a.853.853,0,0,1,.777,1.518L2.705,19.2l17.752,9.643L38.21,19.2l-1.117-.575a.853.853,0,0,1,.778-1.518l2.531,1.3a.854.854,0,0,1,.038,1.518L20.863,30.566a.848.848,0,0,1-.811,0L.473,19.932a.854.854,0,0,1,.017-1.509Zm0,8.533,2.553-1.313a.853.853,0,0,1,.777,1.518l-1.116.575,17.752,9.643L38.21,27.737l-1.117-.576a.853.853,0,0,1,.778-1.518l2.531,1.3a.853.853,0,0,1,.038,1.518L20.863,39.1a.848.848,0,0,1-.811,0L.473,28.466a.854.854,0,0,1,.017-1.509Z"
                                  transform="translate(-0.026 0.051)" />
                        </svg>

                        {{$item->trans_title}}
                    </a>
                </li>
            @endforeach


        </ul>
    </div>
</nav>

<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <?php
            $url = '';
            if(auth()->user()->upload) {
                // if (file_exists(auth()->user()->upload->file) == false){
                //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                // }else{
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
                // }
            }else {
                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . auth()->user()->trans_name;
            }
        ?>
        {{-- @if (file_exists($url)) --}}
        <div class="person-wrapper">
            <img src="{{$url}}" alt=" ">
            <h2 style="font-size: 20px; margin: 0;">{{auth()->user()->trans_name}}</h2>
            {{-- <medium style="color: #73726c; font-weight: 700; text-transform: capitalize;">{{$user_role_name}}</medium> --}}
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

            @foreach($user_sidebar_courses->courses as $item)
                <li class="nav-item">
                    <a class="nav-link {{ (url()->full() == CustomRoute('user.course_details',$item->id)) && (url()->full() != CustomRoute('user.home'))  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24.567" height="23.684" viewBox="0 0 24.567 23.684">
                            <defs>
                              <clipPath id="clip-path">
                                <path id="Path_79" data-name="Path 79" d="M5.872-23.684a.527.527,0,0,0-.528.528h0v10.488H.527A.526.526,0,0,0,0-12.141H0V-3.2A3.2,3.2,0,0,0,3.2,0H21.367a3.2,3.2,0,0,0,3.2-3.2h0V-23.156a.527.527,0,0,0-.528-.528H5.872ZM6.4-3.2v-19.43H23.512V-3.2a2.148,2.148,0,0,1-2.145,2.144H5.573A3.185,3.185,0,0,0,6.4-3.2m-5.344,0v-8.415H5.344V-3.2A2.147,2.147,0,0,1,3.2-1.055h0A2.146,2.146,0,0,1,1.056-3.2m11.181-17.69L9.722-18.134l-.783-.829a.529.529,0,0,0-.746-.022h0a.528.528,0,0,0-.021.747h0L9.345-17a.526.526,0,0,0,.384.165h0A.526.526,0,0,0,10.119-17h0l2.9-3.173a.526.526,0,0,0-.034-.745h0a.523.523,0,0,0-.356-.138h0a.527.527,0,0,0-.39.171m1.978,2.244a.527.527,0,0,0-.528.527h0a.527.527,0,0,0,.528.528h6.763a.527.527,0,0,0,.527-.528h0a.526.526,0,0,0-.527-.527H14.215Zm-1.978,3.8L9.722-12.087l-.783-.829a.529.529,0,0,0-.746-.022h0a.528.528,0,0,0-.021.747h0l1.173,1.242a.529.529,0,0,0,.384.165h0a.533.533,0,0,0,.386-.172h0l2.9-3.174a.526.526,0,0,0-.034-.745h0a.526.526,0,0,0-.355-.138h0a.531.531,0,0,0-.391.172m1.978,1.991a.528.528,0,0,0-.528.527h0a.527.527,0,0,0,.528.528h6.763a.527.527,0,0,0,.527-.528h0a.527.527,0,0,0-.527-.527H14.215ZM12.237-8.794,9.722-6.04l-.783-.829a.528.528,0,0,0-.746-.02h0a.526.526,0,0,0-.021.745h0L9.345-4.9a.529.529,0,0,0,.384.165h0a.53.53,0,0,0,.386-.172h0l2.9-3.173a.527.527,0,0,0-.034-.746h0a.526.526,0,0,0-.355-.138h0a.531.531,0,0,0-.391.172m1.978,1.74a.527.527,0,0,0-.528.527h0A.527.527,0,0,0,14.215-6h6.763a.527.527,0,0,0,.527-.528h0a.526.526,0,0,0-.527-.527H14.215Z" fill="#bdbdbd"/>
                              </clipPath>
                            </defs>
                            <g id="Group_57" data-name="Group 57" transform="translate(0 23.684)">
                              <g id="Group_56" data-name="Group 56" clip-path="url(#clip-path)">
                                <g id="Group_55" data-name="Group 55" transform="translate(12.284 -11.842)">
                                  <path id="Path_78" data-name="Path 78" d="M-12.284-11.842H12.284V11.842H-12.284Z" fill="#bdbdbd"/>
                                </g>
                              </g>
                            </g>
                        </svg>
                        {{$item->trans_title}}
                    </a>
                </li>
            @endforeach

            <li class="admin title">
                <svg xmlns="http://www.w3.org/2000/svg" width="28.126" height="28.127" viewBox="0 0 28.126 28.127">
                    <g id="Group_71" data-name="Group 71" transform="translate(-92 -5.528)">
                      <path id="Path_52" data-name="Path 52" d="M111.447,9.044H108.48V8.9a1.593,1.593,0,0,0-1.592-1.592H105.87a2.973,2.973,0,0,0-2.72-1.783h-.106a2.973,2.973,0,0,0-2.72,1.783H99.305A1.594,1.594,0,0,0,97.713,8.9v.141H94.747c-1.72,0-2.747.822-2.747,2.2V30.908a2.75,2.75,0,0,0,2.747,2.747h16.7a2.75,2.75,0,0,0,2.747-2.747V18.474a.549.549,0,1,0-1.1,0V30.908a1.65,1.65,0,0,1-1.648,1.648h-16.7A1.65,1.65,0,0,1,93.1,30.908V11.241c0-.272,0-1.1,1.648-1.1h2.966v.769a.549.549,0,0,0,.549.549h9.668a.549.549,0,0,0,.549-.549v-.769h2.966a1.65,1.65,0,0,1,1.648,1.648V13.64a.549.549,0,1,0,1.1,0V11.79a2.75,2.75,0,0,0-2.747-2.747Zm-4.065,1.318h-8.57V8.9a.494.494,0,0,1,.493-.493h1.406a.55.55,0,0,0,.53-.406,1.87,1.87,0,0,1,1.8-1.377h.106A1.871,1.871,0,0,1,104.952,8a.549.549,0,0,0,.53.406h1.407a.493.493,0,0,1,.493.493Zm0,0" fill="#bdbdbd"/>
                      <path id="Path_53" data-name="Path 53" d="M399.748,149.137a.549.549,0,1,0,.388.16.553.553,0,0,0-.388-.16Zm0,0" transform="translate(-286.104 -133.748)" fill="#bdbdbd"/>
                      <path id="Path_54" data-name="Path 54" d="M486.151,263.13a.549.549,0,1,0,.388.161.553.553,0,0,0-.388-.161Zm0,0" transform="translate(-366.574 -239.913)" fill="#bdbdbd"/>
                      <path id="Path_55" data-name="Path 55" d="M438.965,96.179l-1.758-3.928a.55.55,0,0,0-1,0l-1.758,3.928a.548.548,0,0,0-.048.224v16.4a1.313,1.313,0,0,0,1.312,1.312H437.7a1.313,1.313,0,0,0,1.312-1.312V106.63a.549.549,0,0,0-1.1,0v3.841H435.5V96.953h2.417v5.07a.549.549,0,1,0,1.1,0V96.4a.548.548,0,0,0-.048-.224Zm-2.259-2.359.91,2.034h-1.821Zm1.209,17.75v1.238a.213.213,0,0,1-.213.213H435.71a.213.213,0,0,1-.213-.213V111.57Zm0,0" transform="translate(-318.886 -80.465)" fill="#bdbdbd"/>
                      <path id="Path_56" data-name="Path 56" d="M154.842,120.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0" transform="translate(-56.378 -107.289)" fill="#bdbdbd"/>
                      <path id="Path_57" data-name="Path 57" d="M154.842,216.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0" transform="translate(-56.378 -196.696)" fill="#bdbdbd"/>
                      <path id="Path_58" data-name="Path 58" d="M154.842,312.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0" transform="translate(-56.378 -286.104)" fill="#bdbdbd"/>
                      <path id="Path_59" data-name="Path 59" d="M253.782,171.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -154.972)" fill="#bdbdbd"/>
                      <path id="Path_60" data-name="Path 60" d="M253.782,133.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -119.211)" fill="#bdbdbd"/>
                      <path id="Path_61" data-name="Path 61" d="M253.782,267.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -244.38)" fill="#bdbdbd"/>
                      <path id="Path_62" data-name="Path 62" d="M253.782,229.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -208.618)" fill="#bdbdbd"/>
                      <path id="Path_63" data-name="Path 63" d="M253.782,363.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -333.788)" fill="#bdbdbd"/>
                      <path id="Path_64" data-name="Path 64" d="M253.782,325.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0" transform="translate(-144.295 -298.026)" fill="#bdbdbd"/>
                    </g>
                  </svg>

                <span>Admin</span>
            </li>
            @foreach($user_pages as $aside)
                <?php
                $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
                $active = ($aside->id==session('infastructure_parent_id')) && url()->full() != CustomRoute('user.home') ? 'active' : '';
                $menu_open = $active=='active'?'menu-open':'';
                ?>

                <li class="nav-item admin {{$has_treeview}} {{$menu_open}}"><!--menu-open-->
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

        </ul>
    </div>
</nav>

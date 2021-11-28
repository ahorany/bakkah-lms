<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <?php
        $url = '';
        if(auth()->user()->upload) {
            $url = auth()->user()->upload->file;
            $url = CustomAsset('upload/full/'. $url);
        }else {
            $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
        }
        ?>
        @if (file_exists($url))
        <div class="person-wrapper">
            <img src="{{$url}}" alt="">
            <h2>{{auth()->user()->trans_name}}</h2>
            <hr>
        </div>
        @endif

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.html">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Path_132" data-name="Path 132"
                              d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                    </svg>

                    Dashboard
                </a>
            </li>

            @foreach($user_sidebar_courses->courses as $item)
                <li class="nav-item">
                    <a class="nav-link {{url()->full() == CustomRoute('user.course_details',$item->id)  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id)}}">
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


            @foreach($user_pages as $aside)
            <?php
              $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
              $active = ($aside->id==session('infastructure_parent_id')) ? 'active' : '';
              $menu_open = $active=='active'?'menu-open':'';
            ?>
            <li class="nav-item {{$has_treeview}} {{$menu_open}}"><!--menu-open-->

                {!!Builder::SidebarHref($aside, '#', $active)!!}

                @if($has_treeview=='has-treeview')
                    <ul class="nav nav-treeview">

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

{{--            @foreach($user_sidebar_courses->courses as $item)--}}
{{--                <li class="nav-item  "><!--menu-open-->--}}
{{--                    <a class="nav-link {{url()->full() == CustomRoute('user.course_details',$item->id)  ? 'active' : '' }}" ><i class="fas fa-tachometer-alt"></i> <p></p></a>--}}
{{--                </li>--}}
{{--            @endforeach--}}

{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">--}}
{{--                        <g id="Group_144" data-name="Group 144" transform="translate(-41 45.472)">--}}
{{--                            <path id="Path_16" data-name="Path 16"--}}
{{--                                  d="M72.875,30.2H45.125A4.129,4.129,0,0,1,41,26.071V1.321A4.128,4.128,0,0,1,45.125-2.8h27.75A4.128,4.128,0,0,1,77,1.321v24.75A4.129,4.129,0,0,1,72.875,30.2ZM45.125-.554A1.877,1.877,0,0,0,43.25,1.321v24.75a1.878,1.878,0,0,0,1.875,1.875h27.75a1.878,1.878,0,0,0,1.875-1.875V1.321A1.877,1.877,0,0,0,72.875-.554Zm0,0"--}}
{{--                                  transform="translate(0 -39.668)" />--}}
{{--                            <path id="Path_17" data-name="Path 17"--}}
{{--                                  d="M75.875,127.446H42.125a1.125,1.125,0,0,1,0-2.25h33.75a1.125,1.125,0,0,1,0,2.25Zm0,0"--}}
{{--                                  transform="translate(0 -158.668)" />--}}
{{--                            <path id="Path_18" data-name="Path 18"--}}
{{--                                  d="M148.793-36.472a1.125,1.125,0,0,1-1.125-1.125v-6.75a1.125,1.125,0,0,1,1.125-1.125,1.125,1.125,0,0,1,1.125,1.125v6.75a1.125,1.125,0,0,1-1.125,1.125Zm0,0"--}}
{{--                                  transform="translate(-99.168)" />--}}
{{--                            <path id="Path_19" data-name="Path 19"--}}
{{--                                  d="M415.457-36.472a1.125,1.125,0,0,1-1.125-1.125v-6.75a1.125,1.125,0,0,1,1.125-1.125,1.125,1.125,0,0,1,1.125,1.125v6.75a1.125,1.125,0,0,1-1.125,1.125Zm0,0"--}}
{{--                                  transform="translate(-347.082)" />--}}
{{--                        </g>--}}
{{--                    </svg>--}}

{{--                    Calender--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="files.html">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"--}}
{{--                         width="32" height="41" viewBox="0 0 32 41">--}}
{{--                        <defs>--}}
{{--                            <clipPath id="clip-path">--}}
{{--                                <rect id="Rectangle_4" data-name="Rectangle 4" width="32" height="41"--}}
{{--                                      transform="translate(-0.486 0)" fill="none" />--}}
{{--                            </clipPath>--}}
{{--                        </defs>--}}
{{--                        <g id="Group_145" data-name="Group 145" transform="translate(-152.514 -18.861)">--}}
{{--                            <g id="Group_6" data-name="Group 6" transform="translate(153 18.861)">--}}
{{--                                <g id="Group_5" data-name="Group 5" transform="translate(0 0)"--}}
{{--                                   clip-path="url(#clip-path)">--}}
{{--                                    <path id="Path_20" data-name="Path 20"--}}
{{--                                          d="M183.165,28.87h-8.619a.864.864,0,0,1-.862-.862V19.39a.862.862,0,0,0-.862-.862H155.586A2.589,2.589,0,0,0,153,21.114v36.2a2.589,2.589,0,0,0,2.586,2.586h25.856a2.589,2.589,0,0,0,2.586-2.586V29.732a.862.862,0,0,0-.862-.862ZM182.3,57.312a.864.864,0,0,1-.862.862H155.586a.864.864,0,0,1-.862-.862v-36.2a.864.864,0,0,1,.862-.862h16.375v7.757a2.589,2.589,0,0,0,2.586,2.586H182.3Zm0,0"--}}
{{--                                          transform="translate(-153 -18.897)" />--}}
{{--                                    <path id="Path_21" data-name="Path 21"--}}
{{--                                          d="M340.808,29.133,330.466,18.791a.862.862,0,0,0-1.219,1.219L339.59,30.352a.862.862,0,1,0,1.219-1.219Zm0,0"--}}
{{--                                          transform="translate(-310.035 -18.907)" />--}}
{{--                                </g>--}}
{{--                            </g>--}}
{{--                        </g>--}}
{{--                    </svg>--}}

{{--                    Private File--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="course.html">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="31.667"--}}
{{--                         viewBox="0 0 38 31.667">--}}
{{--                        <path id="Path_133" data-name="Path 133"--}}
{{--                              d="M36.338,31.667H1.654A1.649,1.649,0,0,1,0,30.083V14.25a.357.357,0,0,1,.135-.291.806.806,0,0,1,.133-.21L3.728.59A.79.79,0,0,1,4.49,0H33.5a.79.79,0,0,1,.762.59l3.459,13.159a.765.765,0,0,1,.132.2.355.355,0,0,1,.146.3V30.083A1.657,1.657,0,0,1,36.338,31.667Zm.079-1.583V15.042H26.075A7.236,7.236,0,0,1,19,21.375a7.2,7.2,0,0,1-7.02-6.333H1.583V30.083Zm-3.523-28.5H5.1L1.975,13.458H12.719a.79.79,0,0,1,.788.792,5.519,5.519,0,1,0,11.038,0,.79.79,0,0,1,.788-.792H36.015Z" />--}}
{{--                    </svg>--}}

{{--                    My Courses--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="exercise.html">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="32.685" height="40.817"--}}
{{--                         viewBox="0 0 32.685 40.817">--}}
{{--                        <g id="Group_146" data-name="Group 146" transform="translate(-143 -18.528)">--}}
{{--                            <path id="Path_122" data-name="Path 122"--}}
{{--                                  d="M258.837,68.879a1.2,1.2,0,0,0,1.2-1.2,1.807,1.807,0,0,1,.017-.248,1.766,1.766,0,1,1,2.217,1.951,2.267,2.267,0,0,0-1.664,2.18v1.5a1.2,1.2,0,0,0,2.392,0v-1.4a4.158,4.158,0,1,0-5.353-3.982,1.2,1.2,0,0,0,1.2,1.2Zm0,0"--}}
{{--                                  transform="translate(-102.455 -40.215)" />--}}
{{--                            <path id="Path_123" data-name="Path 123"--}}
{{--                                  d="M287.892,181.5a1.2,1.2,0,1,1-1.2-1.2,1.2,1.2,0,0,1,1.2,1.2Zm0,0"--}}
{{--                                  transform="translate(-127.353 -144.578)" />--}}
{{--                            <path id="Path_124" data-name="Path 124"--}}
{{--                                  d="M175.452,24.8l-6.043-6.043a.8.8,0,0,0-.564-.233H143.8a.8.8,0,0,0-.8.8V58.548a.8.8,0,0,0,.8.8h31.091a.8.8,0,0,0,.8-.8V25.369a.8.8,0,0,0-.233-.564ZM144.594,57.75V20.122H168.51v4.783a.8.8,0,0,0,.8.8h4.783V57.75Zm0,0" />--}}
{{--                            <path id="Path_125" data-name="Path 125"--}}
{{--                                  d="M216.225,219.714a.8.8,0,0,0-.555-.225H198.719a.8.8,0,0,0-.8.8v4.783a.8.8,0,0,0,.8.8H215.67a.8.8,0,0,0,.555-.225l2.465-2.392a.8.8,0,0,0,0-1.144Zm-.879,4.558H204.27v-3.189h11.077l1.643,1.594Zm0,0"--}}
{{--                                  transform="translate(-49.084 -179.6)" />--}}
{{--                            <path id="Path_126" data-name="Path 126"--}}
{{--                                  d="M216.225,302.253a.8.8,0,0,0-.555-.225H198.719a.8.8,0,0,0-.8.8v4.783a.8.8,0,0,0,.8.8H215.67a.8.8,0,0,0,.555-.225l2.465-2.392a.8.8,0,0,0,0-1.144Zm-.879,4.558H204.27v-3.189h11.077l1.643,1.594Zm0,0"--}}
{{--                                  transform="translate(-49.084 -253.366)" />--}}
{{--                        </g>--}}
{{--                    </svg>--}}

{{--                    Exercise--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="exam.html">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"--}}
{{--                         width="47" height="42" viewBox="0 0 47 42">--}}
{{--                        <defs>--}}
{{--                            <clipPath id="clip-path">--}}
{{--                                <rect id="Rectangle_110" data-name="Rectangle 110" width="47" height="42"--}}
{{--                                      transform="translate(-0.412 0)" fill="#fff" />--}}
{{--                            </clipPath>--}}
{{--                        </defs>--}}
{{--                        <g id="Group_150" data-name="Group 150" transform="translate(-104.588 -37.284)">--}}
{{--                            <path id="Path_134" data-name="Path 134"--}}
{{--                                  d="M155.3,327.454H143.414a.914.914,0,0,0,0,1.828H155.3a.914.914,0,0,0,0-1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -254.558)" fill="#fff" />--}}
{{--                            <path id="Path_135" data-name="Path 135"--}}
{{--                                  d="M143.414,298.176h5.941a.914.914,0,1,0,0-1.828h-5.941a.914.914,0,0,0,0,1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -227.243)" fill="#fff" />--}}
{{--                            <path id="Path_136" data-name="Path 136"--}}
{{--                                  d="M155.3,265.243H143.414a.914.914,0,0,0,0,1.828H155.3a.914.914,0,0,0,0-1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -199.928)" fill="#fff" />--}}
{{--                            <path id="Path_137" data-name="Path 137"--}}
{{--                                  d="M143.414,235.961h5.941a.914.914,0,1,0,0-1.828h-5.941a.914.914,0,0,0,0,1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -172.61)" fill="#fff" />--}}
{{--                            <path id="Path_138" data-name="Path 138"--}}
{{--                                  d="M155.3,203.028H143.414a.914.914,0,0,0,0,1.828H155.3a.914.914,0,0,0,0-1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -145.296)" fill="#fff" />--}}
{{--                            <path id="Path_139" data-name="Path 139"--}}
{{--                                  d="M143.414,173.751h5.941a.914.914,0,0,0,0-1.828h-5.941a.914.914,0,0,0,0,1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -117.982)" fill="#fff" />--}}
{{--                            <path id="Path_140" data-name="Path 140"--}}
{{--                                  d="M184.937,140.813h-7.769a.914.914,0,1,0,0,1.828h7.769a.914.914,0,1,0,0-1.828Zm0,0"--}}
{{--                                  transform="translate(-62.57 -90.663)" fill="#fff" />--}}
{{--                            <path id="Path_141" data-name="Path 141"--}}
{{--                                  d="M150.269,110.622a.914.914,0,0,0-.914-.914h-5.941a.914.914,0,0,0,0,1.828h5.941a.914.914,0,0,0,.914-.914Zm0,0"--}}
{{--                                  transform="translate(-32.93 -63.349)" fill="#fff" />--}}
{{--                            <path id="Path_142" data-name="Path 142"--}}
{{--                                  d="M155.3,78.6H143.414a.914.914,0,0,0,0,1.828H155.3a.914.914,0,0,0,0-1.828Zm0,0"--}}
{{--                                  transform="translate(-32.93 -36.034)" fill="#fff" />--}}
{{--                            <g id="Group_149" data-name="Group 149" transform="translate(105 37.284)">--}}
{{--                                <g id="Group_148" data-name="Group 148" transform="translate(0 0)"--}}
{{--                                   clip-path="url(#clip-path)">--}}
{{--                                    <path id="Path_143" data-name="Path 143"--}}
{{--                                          d="M285.5,83.952a.914.914,0,0,0,1.292,0l3.791-3.791a.914.914,0,0,0-1.293-1.292l-3.144,3.145-1.25-1.25a.914.914,0,0,0-1.293,1.293Zm0,0"--}}
{{--                                          transform="translate(-261.6 -73.316)" fill="#fff" />--}}
{{--                                    <path id="Path_144" data-name="Path 144"--}}
{{--                                          d="M151.247,52.736,150.04,51.53a1.891,1.891,0,0,0-2.671,0L144.9,54a.913.913,0,0,0-1.259,1.258l-5.736,5.736V39.856a1.83,1.83,0,0,0-1.828-1.828H106.828A1.83,1.83,0,0,0,105,39.856v38.39a1.83,1.83,0,0,0,1.828,1.828h29.249a1.83,1.83,0,0,0,1.828-1.828v-9.5l9.614-9.614a.913.913,0,0,0,1.259-1.258l2.469-2.469a1.891,1.891,0,0,0,0-2.671ZM132.356,71.713l-2.155.862.862-2.155,13.854-13.853,1.293,1.293Zm3.721,6.533H106.828V39.856h29.249V62.821l-6.439,6.439a.91.91,0,0,0-.2.307l-1.724,4.309a.914.914,0,0,0,1.188,1.188l4.309-1.723a.926.926,0,0,0,.307-.2l2.561-2.561Zm13.877-24.131L147.5,56.566l-1.292-1.292,2.452-2.452a.064.064,0,0,1,.086,0l1.206,1.206a.064.064,0,0,1,0,.086Zm0,0"--}}
{{--                                          transform="translate(-105 -37.688)" fill="#fff" />--}}
{{--                                </g>--}}
{{--                            </g>--}}
{{--                            <path id="Path_145" data-name="Path 145"--}}
{{--                                  d="M285.5,146.164a.913.913,0,0,0,1.292,0l3.791-3.791a.914.914,0,0,0-1.293-1.293l-3.144,3.145-1.25-1.249a.914.914,0,0,0-1.293,1.293Zm0,0"--}}
{{--                                  transform="translate(-156.6 -90.663)" fill="#fff" />--}}
{{--                            <path id="Path_146" data-name="Path 146"--}}
{{--                                  d="M286.144,208.645a.909.909,0,0,0,.646-.267l3.791-3.791a.914.914,0,0,0-1.293-1.293l-3.144,3.145-1.25-1.249a.914.914,0,0,0-1.293,1.292l1.9,1.9a.911.911,0,0,0,.647.267Zm0,0"--}}
{{--                                  transform="translate(-156.6 -145.294)" fill="#fff" />--}}
{{--                            <path id="Path_147" data-name="Path 147"--}}
{{--                                  d="M143.418,140.813a.914.914,0,1,0,.915.914.914.914,0,0,0-.915-.914Zm0,0"--}}
{{--                                  transform="translate(-32.933 -90.663)" fill="#fff" />--}}
{{--                        </g>--}}
{{--                    </svg>--}}


{{--                    Exam--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>

    </div>
</nav>

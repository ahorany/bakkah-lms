@extends(FRONT.'.education.layouts.master')

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">

            @include('userprofile::users.sidebar')

            <div class="col-md-9 col-lg-10">
                <div class="main-user-content mx-4 my-5">
                    <div class="row mb-2">
                        <div class="col-lg-8 mb-3">
                            <div class="p-4 user-info card h-100">
                                <div class="d-md-flex align-items-center">
                                    <?php
                                        $url = '';
                                        if(auth()->user()->upload) {
                                            $url = auth()->user()->upload->file;
                                            $url = CustomAsset('upload/full/'. $url);
                                        }else {
                                            $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                                        }
                                    ?>
                                    <div class="img">
                                            <img src="{{ $url }}" alt="">
                                            {{-- <img src="{{CustomAsset('images\profile.png')}}" alt=""> --}}
                                    </div>
                                    <div class="text mx-4">
                                        <h2>Hi <span class="main-color">{{auth()->user()->trans_name}}</span></h2>
                                        <p>{{auth()->user()->bio}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-4 mb-3 d-none">
                            <div class="card user-activitay pb-2">
                                <h4 class="main-color mt-0 mb-2 p-4">{{__('education.Last Activities')}}</h4>
                                <ul>
                                        <li class="px-4"><span>1</span>Welcome to your all event Callender.</li>
                                        <li class="px-4"><span>2</span>Welcome to your all event Callender.</li>
                                        <li class="px-4"><span>3</span>Welcome to your all event Callender.</li>
                                        <li class="px-4"><span>4</span>Welcome to your all event Callender.</li>
                                        <li class="px-4"><span>5</span>Welcome to your all event Callender.</li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>

                    {{-- <div class="row d-none">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{CustomAsset('images\home1.png')}}" alt="" width="60px">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{CustomAsset('images\home2.png')}}" alt="" width="60px">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{CustomAsset('images\home3.png')}}" alt="" width="60px">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{CustomAsset('images\home4.png')}}" alt="" width="60px">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                    </div> --}}
                </div>

            </div>
        </div>
    </div>

@endsection

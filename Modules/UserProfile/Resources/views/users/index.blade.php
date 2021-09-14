@extends(FRONT.'.education.layouts.master')

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">

            @include('userprofile::users.sidebar')

            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="row mb-5">
                        <div class="col-lg-8 mb-4">
                            <div class="card p-4 user-info">
                                <div class="d-md-flex align-items-center">
                                    @if(isset(auth()->user()->upload->file))
                                    <div class="img">
                                        @if(isset($user->upload->file))
                                            <img src="{{CustomAsset('upload/full/'.$user->upload->file)}}" alt="">
                                        @endif
                                    </div>
                                    @endif
                                    <div class="text mx-4">
                                        <h2>Hi <span class="main-color">{{$user->trans_name}}</span></h2>
                                        {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Id, atque exercitationem quibusdam sed eum in optio temporibus incidunt harum deserunt voluptas fugit molestiae nobis reiciendis aliquam quas dolor labore accusamus?</p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card p-4 user-activitay">
                                <h4 class="main-color mb-4">{{__('education.Last Activities')}}</h4>
                                <ul>
                                    @foreach ($user->carts->take(4) as $cart)
                                        <li><span>{{$loop->iteration}}.</span> Register in {{$cart->course->trans_title}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row d-none">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://placehold.it/70x70" alt="">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://placehold.it/70x70" alt="">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://placehold.it/70x70" alt="">
                                    <p class="my-0 lead mx-4">Top User</p>
                                </div>
                            </div><!-- /.user-badge -->
                        </div>

                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card user-badge p-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://placehold.it/70x70" alt="">
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

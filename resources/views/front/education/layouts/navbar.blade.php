<div class="menu-overlay"></div>
<nav id="navbar" class="navbar navbar-expand-xl navbar-light bg-white p-0">
    <div class="container-fluid d-flex align-items-center">
        <div class="col-4 col-sm-1 site-logo">
            <span itemprop="headline">
                <a class="navbar-brand" href="{{route('education.index')}}" title="{{__('education.header_title')}}">
                    <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('education.header_title')}}">
                </a>
            </span>
        </div> <!-- /.col-1 -->
        <div class="col col-sm-11 site-menu">
            <a class="btn btn-primary px-3 btn-sm d-xl-none" href="{{route('education.hot-deals')}}">{{__('education.Hot Deals')}}</a>
            <button class="navbar-toggler d-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>

            <div class="collapse navbar-collapse d-flex" id="navbarSupportedContent">
                <div class="d-flex">

                    @include(FRONT.'.education.layouts.explore')

                    <div id="search-wrapper">
                        <autocomplete />
                    </div>
                </div>

                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    <li class="nav-item">
                        <a class="nav-link hot-deals d-none d-xl-block" href="{{route('education.hot-deals')}}">{{__('education.Hot Deals')}}</a>
                        <a href="{{route('education.courses')}}" class="nav-link hot-deals w-100 btn-sm d-xl-none">{{__('education.explore_all_courses')}}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{route('education.static.knowledge-hub')}}" id="navbarDropdown">
                            {{__('education.Knowledge Hub')}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="nav-link dropdown-item {{\Request::url()==route('education.static.knowledge-center', ['post_type'=>'knowledge-center'])?'active':''}}" href="{{route('education.static.knowledge-center', ['post_type'=>'knowledge-center'])}}">{{__('education.Knowledge Center')}}</a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('education.static.webinars')?'active':''}}" href="{{route('education.static.webinars')}}">{{__('education.webinars')}}</a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('education.static.reports')?'active':''}}" href="{{route('education.static.reports')}}">{{__('education.Reports')}}</a>
                        </div>
                    </li>
                    <li class="nav-item d-xl-none">
                        <a class="nav-link {{\Request::url()==route('education.training-schedule')?'active':''}}" href="{{route('education.training-schedule')}}">{{__('education.Training Schedule')}}</a>
                    </li>
                    <li class="nav-item d-none">
                        <a class="nav-link {{\Request::url()==route('education.static.knowledge-center', ['post_type'=>'knowledge-center'])?'active':''}}" href="{{route('education.static.knowledge-center', ['post_type'=>'knowledge-center'])}}">{{__('education.Knowledge Center')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{\Request::url()==route('education.for-corporate')?'active':''}}" href="{{route('education.for-corporate')}}">{{__('education.For Corporate')}}</a>
                    </li>
                    @auth
                    <li id="user" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle cart-item" href="{{route('user.my_courses')}}">
                            <div class="cart-wrapper">
                                <?php
                                    $url = '';
                                    if(auth()->user()->upload) {
                                        $url = auth()->user()->upload->file;
                                        $url = CustomAsset('upload/full/'. $url);
                                    }else {
                                        $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                                    }
                                ?>
                                <img style="width:25px;height:25px;object-fit:cover;border-radius: 50%;" src="{{$url}}" />
                                {{-- <i class="far fa-user"></i> --}}
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a style="background: #f8f9fa;white-space: unset;" class="nav-link dropdown-item">
                                <span class="d-block py-2 main-color">{{__('education.Welcome,')}} {{ auth()->user()->trans_name }}</span>
                            </a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('user.info')?'active':''}}" href="{{route('user.info')}}"><i class="far fa-user"></i> <span class="mx-1">{{__('education.Info')}}</span></a>
                            {{-- <a class="nav-link dropdown-item {{\Request::url()==route('user.my_courses')?'active':''}}" href="{{route('user.my_courses')}}"><i class="fas fa-graduation-cap"></i> <span class="mx-1">{{__('education.My Courses')}}</span></a> --}}
                            {{-- <a class="nav-link dropdown-item {{\Request::url()==route('user.wishlist')?'active':''}}" href="{{route('user.wishlist')}}"><i class="far fa-heart"></i> <span class="mx-1">{{__('education.Wishlists')}}</span></a> --}}
                            <a class="nav-link dropdown-item" href="{{route('user.change_password')}}"><i class="fas fa-lock"></i> <span class="mx-1">{{__('education.Change Password')}}</span></a>
                            {{-- <a class="nav-link dropdown-item {{\Request::url()==route('user.payment_history')?'active':''}}" href="{{route('user.payment_history')}}">{{__('education.Payment History')}}</a> --}}
                            <a class="nav-link dropdown-item" href="{{route('user.logout')}}"><i class="fas fa-sign-out-alt"></i> <span class="mx-1">{{__('education.Logout')}}</span></a>
                        </div>
                    </li>

                    <li id="wishlist" class="nav-item dropdown">
                        <a  class="nav-link dropdown-toggle cart-item" href="{{route('education.wishlist')}}">
                            <div class="cart-wrapper">
                                <i class="far fa-heart"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right cart-items-wrapper" aria-labelledby="navbarDropdown">
                            <div class="items-wrapper">
                                <div v-for="wishlist in wishlists">
                                    <div class="p-3">
                                        <a :href="'/sessions/'+wishlist.training_option.course.slug" class="d-flex align-items-center">
                                            <div class="course-image">
                                                <img :src="`{{env('APP_URL')}}{{env('LIVE_ASSET')}}upload/thumb300/${wishlist.training_option.course.upload.file}`" alt="" width="100" height="100">
                                            </div>
                                            <div class="shopping-item">
                                                <div class="course-title">
                                                    @if(app()->getLocale() == 'en')
                                                        @{{JSON.parse(wishlist.training_option.course.title).en}}
                                                    @else
                                                        @{{JSON.parse(wishlist.training_option.course.title).ar}}
                                                    @endif
                                                    </div>
                                            </div>
                                        </a>
                                        <button class="btn btn-outline-primary btn-sm btn-block mt-2"  @click="addCartItem(wishlist.training_option.course.id, wishlist.session_id, 'wishlist', wishlist.id)">{{__('education.Add to Cart')}}</button>
                                    </div>
                                    <hr class="m-0">
                                </div>
                            </div>
                            <div class="p-3" v-if="wishlists.length == 0">
                                <div class="text-center">
                                    <p class="mb-2">{{__('education.Your wishlist is empty.')}}</p>
                                    <a href="{{route('education.courses')}}">{{__('education.Explore courses')}}</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endauth

                    @guest
                    <li class="nav-item">
                        <a class="nav-link {{\Request::url()==route('user.login')?'active':''}}" href="{{route('user.login')}}">{{__('education.Login')}}</a>
                    </li>
                    @endguest

                <li id="cart" class="nav-item dropdown">
                    <a  class="nav-link dropdown-toggle cart-item" href="{{route('education.cart')}}">
                        <div class="cart-wrapper">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" v-if="typeof CartWithDetails.carts != 'undefined'" v-html="CartWithDetails.carts.length"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right cart-items-wrapper" aria-labelledby="navbarDropdown">
                        <div class="items-wrapper">
                            <div v-if="typeof CartWithDetails.carts != 'undefined'" v-for="cart in CartWithDetails.carts">
                                <div class="p-3">
                                    <a :href="'/sessions/'" class="d-flex align-items-start">
                                        <div class="course-image">
                                            <img :src="ImageUrl(cart.course.upload.file, 'thumb100')" alt="" width="100" height="100">
                                        </div>
                                        <div class="shopping-item">
                                            <div class="course-title">
                                                @{{convertJson(cart.course.title)}}
                                                </div>
                                            <span class="course-price main-color">@{{cart.total_after_vat}} <small>@{{currency}}</small></span>
                                        </div>
                                    </a>
                                    @unless (strpos(url()->full(), "/checkout") !== false)
                                        <button class="delete" @click="deleteCartItem(cart.id)"><i class="fas fa-times"></i></button>
                                    @endunless
                                </div>
                                <hr class="m-0">
                            </div>
                        </div>
                        <div class="p-3">
                            <div v-if="typeof CartWithDetails.carts != 'undefined'">
                                <h4 class="boldfont">{{__('education.Total')}}: @{{cartTotal}} @{{currency}} </h4>
                                <a href="{{route('education.cart')}}" class="btn btn-primary btn-block boldfont">{{__('education.Cart')}} <i class="fas fa-shopping-cart"></i></a>
                            </div>
                            <div class="text-center" v-else>
                                <p class="mb-2">{{__('education.Your cart is empty.')}}</p>
                                <a href="{{route('education.courses')}}">{{__('education.Keep shopping')}}</a>
                            </div>
                        </div>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{route('user.logout')}}"> <span class="mx-1">{{__('education.Logout')}}</span></a>
                    </li> --}}
                    @include(FRONT.'.Html.switch-lang')
                </ul>
            </div>
        </div> <!-- /.col-11 -->
    </div>
</nav> <!-- /#navbar -->


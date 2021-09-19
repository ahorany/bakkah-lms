@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(94)??null])
@endsection

<style>
    body .table td, body .table th {
        vertical-align: middle;
    }
</style>

@section('content')
@include(FRONT.'.education.Html.page-header', ['title'=>__('education.Cart')])

<div class="main-content py-5">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-12">
                <table class="table table-hover">
                <thead>
                <tr>
                <th>Product</th>
                <th class="text-center">Price</th>
                <th class="text-center">Total</th>
                <th> </th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="cart in carts">
                        <td>
                            <div class="media align-items-center">
                                <a class="thumbnail pull-left" href="#"> <img class="media-object" :src="`/upload/thumb300/${cart.course.upload.file}`" style="width: 80px; height: 80px; object-fit: contain;"> </a>
                                <div class="media-body mx-3">
                                    <h4 class="media-heading"><a :href="'sessions/'+cart.course.slug">
                                    @if(app()->getLocale() == 'en')
                                        @{{JSON.parse(cart.course.title).en}}
                                    @else
                                        @{{JSON.parse(cart.course.title).ar}}
                                    @endif
                                    </a></h4>

                                    <ul>
                                        <li v-for="feature in cart.training_option.training_option_feature">
                                            @{{feature.feature.id}}
                                            @if(app()->getLocale() == 'en')
                                                @{{JSON.parse(feature.feature.title).en}}
                                            @else
                                                @{{JSON.parse(feature.feature.title).ar}}
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <strong>@{{currency}} @{{cart.price}} </strong>
                        </td>
                        <td class=" text-center">
                            <strong>@{{currency}} @{{cart.price}}</strong>
                        </td>
                        <td class=" text-center">
                            <button @click="deleteCartItem(cart.id)" type="button" class="btn btn-primary btn-sm"><span class="fas fa-times"></span></button>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div class="col-md-5  bg-light p-4">
                <table class="table m-0">
                    <tr>
                        <td><h5>{{__('education.Sub Total')}}</h5></td>
                        <td class="text-right"><h5><strong>@{{currency}} @{{cartTotal}}</strong></h5></td>
                        </tr>
                        <tr>
                        <td><h3>{{ __('education.Total') }}</h3></td>
                        <td class="text-right"><h3><strong>@{{currency}} @{{cartTotal}}</strong></h3></td>
                        </tr>
                        <tr>
                        <td>
                        <a href="{{route('education.courses')}}" type="button" class="btn btn-secondary btn-block">
                        <span class="fa fa-shopping-cart"></span> {{ __('education.Continue Shopping') }}
                        </a></td>
                            <td>
                                <?php
                                    $url = route('user.login') . '/?redirectTo=checkout';
                                    if(Auth::check()) {
                                        $url = route('epay.checkout', session('user_token'));
                                    }
                                ?>
                                <a href="{{ $url }}" type="button" class="btn btn-primary btn-block">Checkout</a>
                            </td>
                        </tr>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

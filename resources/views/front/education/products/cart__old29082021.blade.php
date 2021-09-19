@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(94)??null])
@endsection

<style>
body .table td, body .table th {
    vertical-align: middle;
}
.table.table-summary td {
    padding-left: 0;
    padding-right: 0;
}
</style>

@section('content')
@include(FRONT.'.education.Html.page-header', ['title'=>__('education.My Cart')])

<div class="main-content py-5">
    <div class="container">
        <div v-if="carts.length != 0 || cartLater.length != 0" class="row justify-content-end">
            <div class="col-md-12">
                <div class="mb-5" v-if="carts.length != 0">
                    {{-- <h3>{{ __('education.Cart') }}</h3> --}}
                    <table class="table table-hover cart-table">
                        <thead>
                            <tr>
                                <th style="width: 35%">{{ __('education.Product') }}</th>
                                <th style="width: 20%">{{ __('education.Promo Code') }}</th>
                                <th style="width: 15%" class="text-center">{{ __('education.Price') }}</th>
                                <th style="width: 15%" class="text-center">{{ __('education.Total') }}</th>
                                <th style="width: 20%"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr v-for="cart in carts"> --}}
                            <tr v-for="cart in carts">
                                <td>
                                    <div class="media align-items-center">
                                        <a class="thumbnail pull-left" :href="'sessions/'+cart.course.slug"> <img class="media-object" :src="`{{env('APP_URL')}}{{env('LIVE_ASSET')}}upload/thumb300/${cart.course.upload.file}`" style="width: 80px; height: 80px; object-fit: contain;"> </a>
                                        <div class="media-body mx-3">
                                            <h5 class="media-heading"><a :href="'sessions/'+cart.course.slug">
                                            @if(app()->getLocale() == 'en')
                                                <span v-html="JSON.parse(cart.course.title).en"></span>
                                                <small class="text-secondary" v-html="JSON.parse(cart.training_option.type.name).en"></small>
                                            @else
                                                <span v-html="JSON.parse(cart.course.title).ar"></span>
                                                <small class="text-secondary" v-html="JSON.parse(cart.training_option.type.name).en"></small>
                                            @endif
                                            </a></h5>

                                            <template v-if="cart.training_option.training_option_feature.length != 0">

                                                <template v-for="feature_item in cart.training_option.training_option_feature">
                                                    <label v-if="feature_item.is_include" class="d-flex">
                                                        <span style="font-size: 23px;width: 30px;"><i class="fas fa-plus-square main-color"></i></span>

                                                        @if(app()->getLocale() == 'en')
                                                            <span v-html="JSON.parse(feature_item.feature.title).en"></span>
                                                        @else
                                                            <span v-html="JSON.parse(feature_item.feature.title).ar"></span>
                                                        @endif

                                                        <!--<span v-html="feature_item.feature.title"></span>-->
                                                        <span v-if="currency_check == 'SAR'" v-html="`- ${feature_item.price} ${currency}`"></span>
                                                        <span v-else v-html="`- ${feature_item.price_usd} ${currency}`"></span>
                                                    </label>

                                                    <label v-else class="chk_container">
                                                        @if(app()->getLocale() == 'en')
                                                            <span v-html="JSON.parse(feature_item.feature.title).en"></span>
                                                        @else
                                                            <span v-html="JSON.parse(feature_item.feature.title).ar"></span>
                                                        @endif
                                                        <!--<span v-html="feature_item.feature.title"></span>-->
                                                        <span v-if="currency_check == 'SAR'" v-html="`- ${feature_item.price} ${currency}`"></span>
                                                        <span v-else v-html="`- ${feature_item.price_usd} ${currency}`"></span>

                                                        <input v-if="currency_check == 'SAR'" :checked="chackIfExist(cart.cart_features, feature_item.id)" type="checkbox"
                                                            @click="addCartFeature(cart.id, feature_item.id, feature_item.price,feature_item.total_after_vat, $event)"
                                                            name="mail_subscribe" value="1">
                                                        <input v-else :checked="chackIfExist(cart.cart_features, feature_item.id)" type="checkbox"
                                                            @click="addCartFeature(cart.id, feature_item.id, feature_item.price_usd,feature_item.total_after_vat, $event)"
                                                            name="mail_subscribe" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </template>

                                            </template>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" :disabled="cart.promo_code" :value="cart.promo_code" placeholder="{{ __('education.Promo Code') }}" class="form-control" :class="cart.promo_code ? 'is-valid' : ''" v-on:blur="PromoCodeCart(cart.id, cart.session_id, $event)" v-on:keypress.enter="PromoCodeCart(cart.id, cart.session_id, $event)">
                                </td>
                                <td class="text-center">
                                    <span v-html="currency"></span> <span v-html="(cart.price - cart.discount_value).toFixed(2)"></span>
                                </td>
                                <td class=" text-center">
                                    <span v-html="currency"></span> <span v-html="(cart.total).toFixed(2)"></span>
                                </td>
                                <td class=" text-center">
                                    <small class="main-color" style="cursor: pointer;font-size: 70%;text-decoration: underline;" @click="moveToSaveLater(cart.id)" class="mx-2">{{ __('education.Save for later') }}</small>
                                    <button @click="deleteCartItem(cart.id)" type="button" class="btn btn-default main-color btn-sm"><span class="fas fa-times"></span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{--
            <div class="col-md-8" v-if="cartLater.length != 0">
                <h3>{{ __('education.Save for later') }}</h3>
                <table class="table table-hover cart-table">
                    <thead>
                        <tr>
                            <th style="width: 50%">{{ __('education.Product') }}</th>
                            <th style="width: 15%" class="text-center">{{ __('education.Price') }}</th>
                            <th style="width: 15%" class="text-center">{{ __('education.Total') }}</th>
                            <th style="width: 35%"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in cartLater">
                            <td>
                                <div class="media align-items-center">
                                    <a class="thumbnail pull-left" :href="'sessions/'+cart.course.slug"> <img class="media-object" :src="`{{env('APP_URL')}}{{env('LIVE_ASSET')}}upload/thumb300/${cart.course.upload.file}`" style="width: 80px; height: 80px; object-fit: contain;"> </a>
                                    <div class="media-body mx-3">
                                        <h4 class="media-heading"><a :href="'sessions/'+cart.course.slug">
                                        @if(app()->getLocale() == 'en')
                                            <span v-html="JSON.parse(cart.course.title).en"></span>
                                            <small class="text-secondary" v-html="JSON.parse(cart.training_option.type.name).en"></small>
                                        @else
                                            <span v-html="JSON.parse(cart.course.title).ar"></span>
                                            <small class="text-secondary" v-html="JSON.parse(cart.training_option.type.name).en"></small>
                                        @endif
                                        </a></h4>

                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span v-html="currency"></span> <span v-html="(cart.total).toFixed(2)"></span>
                            </td>
                            <td class=" text-center">
                                <span v-html="currency"></span> <span v-html="(cart.total).toFixed(2)"></span>
                            </td>
                            <td class=" text-center">
                                <small class="main-color" style="cursor: pointer;font-size: 70%;text-decoration: underline;" @click="moveToCart(cart.id)" class="mx-2">{{ __('education.Move to Cart') }}</small>
                                <button @click="deleteCartItem(cart.id)" type="button" class="btn btn-default main-color btn-sm"><span class="fas fa-times"></span></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            --}}

            <div class="col-md-4">
                <div class="bg-light p-3">
                    <table class="table m-0 table-summary">
                        <tr>
                            <td>
                                <h6>{{__('education.Sub Total')}}</h6>
                            </td>
                            <td class="text-right">
                                <h6><strong><span v-html="currency"></span> <span>@{{sub_total}}</span></strong></h6>
                            </td>
                        </tr>
                        <tr class="text-success" v-if="bundle_discount!=0">
                            <td>
                                <h6>{{__('education.Bundle Discount')}}</h6>
                            </td>
                            <td class="text-right">
                                <h6><strong><span v-html="currency"></span> -@{{bundle_discount}}</strong></h6>
                            </td>
                        </tr>
                        <tr v-if="vat != 0">
                            <td><h6>{{ __('education.VAT') }} <span v-html="`(${vat * 100}%)`"></span></h6></td>
                            <td class="text-right"><h6><strong><span v-html="currency"></span> <span>@{{vat_value}}</span></strong></h6></td>
                        </tr>
                        <tr>
                            <td><h4>{{ __('education.Total') }}</h4></td>
                            <td class="text-right"><h4><strong><span v-html="currency"></span> <span>@{{total_after_vat}}</span></strong></h4></td>
                        </tr>
                            <tr>
                            <td colspan="2">
                                <?php
                                $url = route('user.login') . '/?redirectTo=checkout';
                                if(Auth::check()) {
                                    $url = route('epay.cartCheckout', Auth::id());
                                }
                                ?>
                                <a href="{{ $url }}" type="button" :class="carts.length == 0 ? 'disabled' : ''" class="btn btn-primary btn-block">{{__('education.Checkout')}}</a>
                                <a href="{{route('education.courses')}}" type="button" class="btn btn-secondary btn-block">{{ __('education.Continue Shopping') }}
                                </a>
                            </td>
                            </tr>
                    </table>
                </div>

            </div>
        </div>
        <div v-else class="row justify-content-center">
            <div class="col-12 text-center">
                <p class="lead">{{ __('education.Your cart is empty.') }} {{ __('education.Keep shopping to find a course!') }}</p>
                <a class="btn btn-primary" href="{{ route('education.courses') }}">{{ __('education.Keep shopping') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

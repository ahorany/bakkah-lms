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
        {{-- || cartLater.length != 0 --}}
        <div v-if="CartWithDetails.carts.length != 0" class="row justify-content-end">
            <div class="col-md-12">
                <div class="mb-5" v-if="CartWithDetails.carts.length != 0">
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
                            <tr v-for="cart in CartWithDetails.carts">
                                <td>
                                    <div class="media align-items-center">
                                        <a class="thumbnail pull-left" :href="'sessions/'">
                                            <img class="media-object" :src="ImageUrl(cart.course.upload.file, 'thumb200')" style="width: 80px; height: 80px; object-fit: contain;">
                                        </a>
                                        <div class="media-body mx-3">
                                            <h5 class="media-heading">
                                                <a :href="'sessions/'">
                                                    <span v-if="cart.course" v-html="convertJson(cart.course.title)"></span>
                                                    <small v-if="cart.training_option" class="text-secondary" v-html="convertJson(cart.training_option.type.name)"></small>
                                                </a>
                                            </h5>

                                            <span class="text-success">cart_id: @{{cart.id}}</span>

                                            <template v-if="cart.training_option" v-if="cart.training_option.training_option_feature.length != 0">

                                                <template v-for="feature_item in cart.training_option.training_option_feature">

                                                    <label v-if="feature_item.is_include" class="d-flex">
                                                        <span style="font-size: 23px;width: 30px;"><i class="fas fa-plus-square main-color"></i></span>

                                                        <span v-html="convertJson(feature_item.feature.title)" v-if="feature_item.feature_id != 5"></span>
                                                        <span v-html="convertJson(feature_item.excerpt)" v-else="feature_item.feature_id == 5"></span>

                                                        <span v-if="currency_check == 'SAR'" v-html="`- ${feature_item.price} ${currency}`"></span>
                                                        <span v-else v-html="`- ${feature_item.price_usd} ${currency}`"></span>
                                                    </label>

                                                    <label v-else class="chk_container">

                                                        <span v-html="convertJson(feature_item.feature.title)" v-if="feature_item.feature_id != 5"></span>
                                                        <span v-html="convertJson(feature_item.excerpt)" v-else="feature_item.feature_id == 5"></span>

                                                        <!--<span v-html="feature_item.feature.title"></span>-->
                                                        <span v-if="currency_check == 'SAR'" v-html="`- ${feature_item.price} ${currency}`"></span>
                                                        <span v-else v-html="`- ${feature_item.price_usd} ${currency}`"></span>

                                                        <input v-if="currency_check == 'SAR'" :checked="IsChecked(cart.cart_features, feature_item.id)" type="checkbox"
                                                            @click="AddCartFeature(cart.id, feature_item.id, 0, $event)"
                                                            name="mail_subscribe" value="1"><!--v-model="features.input[cart.id + '-' + feature_item.id]"-->
                                                        <input v-else :checked="IsChecked(cart.cart_features, feature_item.id)" type="checkbox"
                                                            @click="AddCartFeature(cart.id, feature_item.id, 0, $event)"
                                                            name="mail_subscribe" value="1"><!--v-model="features.input[cart.id + '-' + feature_item.id]"-->
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </template>

                                            </template>

                                        </div>
                                    </div>
                                </td>
{{--                      end problem          --}}
                                <td>
                                    <input type="text" :disabled="cart.promo_code" :value="cart.promo_code" placeholder="{{ __('education.Promo Code') }}" class="form-control" :class="cart.promo_code ? 'is-valid' : ''" v-on:blur="PromoCodeCart(cart.id, $event)" v-on:keypress.enter="PromoCodeCart(cart.id, $event)">
                                </td>
                                <td class="text-center">
                                    <span v-html="currency"></span>
                                    <span>
                                        @{{ TotalByCurrency1(cart).toFixed(0) }}
                                    </span>
                                </td>
                                <td class=" text-center">
                                    <span v-html="currency"></span>
                                    {{-- <span>@{{(cart.price - cart.discount_value).toFixed(2)}}</span> --}}
                                    @{{ TotalFeatures(cart).toFixed(0) }}
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

            <div class="col-md-4">
                <div class="bg-light p-3">
                    <table class="table m-0 table-summary">
                        <tr>
                            <td>
                                <h6>{{__('education.Sub Total')}}</h6>
                            </td>
                            <td class="text-right">
                                <h6><strong><span v-html="currency"></span> <span>@{{CartWithDetails.cartMaster.total}}</span></strong></h6>
                            </td>
                        </tr>
                        <tr v-if="vat != 0">
                            <td><h6>{{ __('education.VAT') }} <span v-html="`(${vat * 100}%)`"></span></h6></td>
                            <td class="text-right"><h6><strong><span v-html="currency"></span> <span>@{{CartWithDetails.cartMaster.vat_value}}</span></strong></h6></td>
                        </tr>
                        <tr>
                            <td><h4>{{ __('education.Total') }}</h4></td>
                            <td class="text-right"><h4><strong><span v-html="currency"></span> <span>@{{CartWithDetails.cartMaster.total_after_vat}}</span></strong></h4></td>
                        </tr>
                            <tr>
                            <td colspan="2">
                                <?php
                                $url = route('user.login') . '/?redirectTo=checkout';
                                if(Auth::check()) {
                                    $url = route('epay.cartCheckout', Auth::id());
                                }
                                ?>
                                <a href="{{ $url }}" type="button" :class="CartWithDetails.carts.length == 0 ? 'disabled' : ''" class="btn btn-primary btn-block">{{__('education.Checkout')}}</a>
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
                <p class="lead">{{ __('education.Your cart is empty.') }} <br> {{ __('education.Keep shopping to find a course!') }}</p>
                <a class="btn btn-primary" href="{{ route('education.courses') }}">{{ __('education.Keep shopping') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
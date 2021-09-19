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
@include(FRONT.'.education.Html.page-header', ['title'=>__('education.Wishlists')])

<div class="main-content py-5">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-12">
                <table class="table table-hover  cart-table">
                    <thead>
                        <tr>
                            <th>{{ __('education.Product') }}</th>
                            {{-- <th style="width: 15%" class="text-center">{{ __('education.Price') }}</th>
                            <th style="width: 20%" class="text-center">{{ __('education.Total') }}</th> --}}
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="wishlist in wishlists">
                            <td>
                                <div class="media align-items-center">
                                    <a class="thumbnail pull-left" :href="'sessions/'+wishlist.training_option.course.slug"> <img class="media-object" :src="`{{env('APP_URL')}}{{env('LIVE_ASSET')}}upload/thumb300/${wishlist.training_option.course.upload.file}`" style="width: 80px; height: 80px; object-fit: contain;"> </a>
                                    <div class="media-body mx-3">
                                        <h4 class="media-heading"><a :href="'sessions/'+wishlist.training_option.course.slug">
                                        @if(app()->getLocale() == 'en')
                                            <span v-html="JSON.parse(wishlist.training_option.course.title).en"></span>
                                        @else
                                            <span v-html="JSON.parse(wishlist.training_option.course.title).ar"></span>
                                        @endif
                                        </a></h4>

                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{-- <span v-html="currency"></span> <span v-html="cart.price"></span> --}}
                            </td>
                            <td class=" text-center">
                                {{-- <span v-html="currency"></span> <span v-html="cartSinglePrice(cart.price, cart.cart_features)"></span> --}}
                            </td>
                            <td class=" text-center">
                                <button @click="deleteWishlistItem(wishlist.id)" type="button" class="btn btn-primary btn-sm"><span class="fas fa-times"></span></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

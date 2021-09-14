<div id="course_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('education.Added to Cart') }}</h5>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" aria-label="Close">
                <small aria-hidden="true">{{ __('education.Continue Shopping') }}</small>
            </button>
        </div>
        <div id="modal-body" class="modal-body pb-0" v-if="lastCartItem.length != 0">

            <div class="modal-cart-item row align-items-center">
                <div class="col-9 mb-4 d-flex align-items-center">
                    <i class="fas fa-check"></i>
                    <img class="mx-2" width="140" style="object-fit: contain;" :src="ImageUrl(lastCartItem.course.upload.file, 'thumb200')">
                    <h5 class="cart-title boldfont m-0">
                        @{{ langCode == 'en' ? JSON.parse(lastCartItem.course.title).en : JSON.parse(lastCartItem.course.title).ar}}
                    </h5>
                </div>
                <div class="col-3 mb-4">
                    <a href="{{route('education.cart')}}" class="btn btn-primary btn-block">{{ __('education.Go to cart') }}</a>
                </div>

                <div class="col-12 mb-2 px-5" v-if="lastCartItem.training_option.training_option_features.length != 0">
                    <h6 class="boldfont">{{__('education.Features with course')}}</h6>
                </div>

                <div class="col-12 mb-2 px-5" v-for="feature_item in lastCartItem.training_option.training_option_features">

                    <label v-if="feature_item.is_include" class="d-flex justify-content-between m-0">
                        <span class="d-flex align-items-center">
                            <span style="font-size: 26px;width: 30px;"><i class="fas fa-plus-square main-color"></i></span>
                            <span>@{{ langCode == 'en' ? JSON.parse(feature_item.feature.title).en : JSON.parse(feature_item.feature.title).ar}}</span>
                        </span>
                        @{{ currency == 'SAR' ? feature_item.price * (vat + 1) : feature_item.price_usd * (vat + 1) }} @{{currency}}
                        {{-- <b v-html="`${feature_item.total_after_vat} ${currency}`"></b> --}}
                    </label>
                    <label v-else class="chk_container d-flex justify-content-between m-0">
                        {{-- <span v-html="`${feature_item.feature.title}`"></span> --}}
                        <span>@{{ langCode == 'en' ? JSON.parse(feature_item.feature.title).en : JSON.parse(feature_item.feature.title).ar}}</span>
                        @{{ currency == 'SAR' ? feature_item.price : feature_item.price_usd }} @{{currency}}
                        <input type="checkbox" :checked="feature_item.is_include" @click="addCartFeature(lastCartItem.cart_id, feature_item.id, feature_item.price, feature_item.total_after_vat, $event)" value="1"> <span class="checkmark"></span>
                    </label>
                </div>

                <div class="col-12 mt-4 px-5 py-3 bg-light">
                    <div class="d-flex d-flex justify-content-between">
                        <h4 class="boldfont m-0">{{ __('education.Total') }}</h4>
                        <h4 class="boldfont m-0" v-html="`${lastCartItem.price} ${currency}`"></h4>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

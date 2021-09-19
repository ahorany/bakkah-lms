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
                    <img class="mx-2" width="140" style="object-fit: contain;" :src="'{{env('APP_URL')}}{{env('LIVE_ASSET')}}upload/thumb300/' + lastCartItem.course.file">
                    <h5 class="cart-title boldfont m-0">
                        @{{ langCode == 'en' ? JSON.parse(lastCartItem.course.title).en : JSON.parse(lastCartItem.course.title).ar}}
                    </h5>
                </div>
                <div class="col-3 mb-4">
                    <a href="{{route('education.cart')}}" class="btn btn-primary btn-block">{{ __('education.Go to cart') }}</a>
                </div>

                <div class="col-12 mb-2 px-5" v-if="lastCartItem.training_option.features.length != 0">
                    <h6 class="boldfont">{{__('education.Features with course')}}</h6>
                </div>

                <div class="col-12 mb-2 px-5" v-for="feature_item in lastCartItem.training_option.features">

                    <label v-if="feature_item.is_include" class="d-flex justify-content-between m-0">
                        <span class="d-flex align-items-center">
                            <span style="font-size: 26px;width: 30px;"><i class="fas fa-plus-square main-color"></i></span>
                            <span>@{{ langCode == 'en' ? JSON.parse(feature_item.feature.title).en : JSON.parse(feature_item.feature.title).ar}}</span>
                            {{-- <span v-html="`${feature_item.feature.title}`"></span> --}}
                        </span>
                        {{-- <b v-html="`${feature_item.total_after_vat} ${currency}`"></b> --}}
                    </label>

                    <label v-else class="chk_container d-flex justify-content-between m-0">
                        {{-- <span v-html="`${feature_item.feature.title}`"></span> --}}
                        <span>@{{ langCode == 'en' ? JSON.parse(feature_item.feature.title).en : JSON.parse(feature_item.feature.title).ar}}</span>
                        {{-- <b v-html="`${feature_item.total_after_vat} ${currency}`"></b> --}}
                        {{-- <input type="checkbox" @click="addCartFeature(lastCartItem.cart_id, feature_item.id, feature_item.price, feature_item.total_after_vat, $event)" :checked="feature_item.is_include" value="1"> <span class="checkmark"></span> --}}
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
</div>
@include(FRONT.'.social_scripts.learning.footer')

{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/popper.min.js')}}'></script>--}}
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/popper.min.js')}}'></script> --}}
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/bootstrap.min.js')}}'></script> --}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/owl.carousel.min.js')}}'></script>--}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/sliders.js')}}'></script>--}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/jquery.cookie.js')}}'></script>--}}
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/wow.min.js')}}'></script>

<!----------TODO:-------------------->
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/script-main.js')}}'></script>
<!----------TODO:-------------------->
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/slick.min.js')}}'></script>
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/all.js')}}'></script>
<script src="{{ CustomAsset('js/app.js') }}"></script>

@stack('scripts')
<script type="text/x-template" id="autocomplete">
    <div class="autocomplete">
        <i class="fas fa-search"></i>
        <input type="text" @input="onChange" v-model="search" @keyup.down="onArrowDown" @keyup.up="onArrowUp" @keyup.enter="onEnter" />
        <ul id="autocomplete-results" v-show="isOpen" ref="scrollContainer" class="autocomplete-results">
        <li class="loading" v-if="isLoading">
            Loading results...
        </li>
        <li ref="options" v-else v-for="(result, i) in results" :key="i" class="autocomplete-result"
        :class="{ 'is-active': i === arrowCounter }">
            @if(app()->getLocale() == 'en')
            <a :href="'{{url('/')}}/sessions/' + result.slug">
                <h5>@{{JSON.parse(result.title).en}}</h5>
                <small>@{{JSON.parse(result.excerpt).en.substr(0, 150)}}...</small>
            </a>
            @else
            <a :href="'{{url('/')}}/ar/sessions/' + result.slug">
                <h5>@{{JSON.parse(result.title).ar}}</h5>
                <small>@{{JSON.parse(result.excerpt).ar.substr(0, 150)}}...</small>
            </a>
            @endif
        </li>
        </ul>
    </div>
</script>
<script>/* type="application/javascript" */
    // const Autocomplete = {
    //     name: "autocomplete",
    //     template: "#autocomplete",
    //     props: {
    //         items: {
    //             type: Array,
    //             required: false,
    //             default: () => []
    //         },
    //         isAsync: {
    //             type: Boolean,
    //             required: false,
    //             default: false
    //         }
    //     },
    //
    //     data() {
    //         return {
    //             isOpen: false,
    //             results: [],
    //             search: "",
    //             isLoading: false,
    //             arrowCounter: 0
    //         };
    //     },
    //
    //     methods: {
    //
    //     },
    //     watch: {
    //
    //     },
    //     mounted() {
    //     },
    //     destroyed() {
    //     }
    // };

    window.sessions = {!! $session_array ?? ''!!}
    window.choose_value = {!! json_encode(__('admin.choose_value')) !!}



    var trainingOptionFeatures = {!! json_encode(old('trainingOptionFeatures', [])) !!};
    // trainingOptionFeatures = sum( trainingOptionFeatures );

    new Vue({
    el: "#page_content",
    // name: "app",
    // components: {
    //     autocomplete: Autocomplete
    // },
    data: {
        carts: @isset($carts)@json($carts)@else [] @endisset,
        cartMaster: @isset($cartMaster)@json($cartMaster)@else [] @endisset,
        test : 'khaled',
        langCode : '{{app()->getLocale()}}',

        bundle_discount: '{{$bundle_discount??0}}',
        vat: '{{ GetCoinId() == 334 ? .15 : 0.00 }}',
        currency: '{{ GetCoinId() == 334 ? __("education.SAR") : __("education.USD") }}',
        currency_check: '{{ GetCoinId() == 334 ? "SAR" : "USD" }}',
        lastCartItem: [],
        wishlists: [],
        cartLater: [],
        cartTotal:0,
        features: {
            // total:{},
            // input:{
            //     '18151-3':true,
            // },
            {{--input:{!! $features??null !!},--}}
            input: @isset($features)@json($features)@else null @endisset,

        },
        promo_code:{

        },
    },
    mounted () {
    },
    watch: {
    },
    methods: {

        chackIfExist: function(arrayOfFeatures, feature_id){
            const checkOptionID = obj => obj.training_option_feature_id == feature_id;
            return arrayOfFeatures.some(checkOptionID);
        },
        AddCartFeature: function(cart_id, feature_id, price, course_price, event) {

            let self = this;
            // self.test = 'ali';
            axios.get("{{route('education.addCartFeature')}}", {
                params:{
                    cart_id: cart_id,
                    feature_id: feature_id,
                    price: price,
                    // feature_action: this.features.input[cart_id + '-' + feature_id],
                }
            })
            .then(function (response){

                self.$set(self.test, 'mohammed', 'mohammed');

                // vm_this.$set(vm_this.features.total, cart_id, response.data.cart.total);
                self.carts.forEach(function(elem,index){
                    if(elem.id == response.data.cart.id) {
                        // vm_this.carts[index] = response.data.cart;
                        self.$set(self.carts, index, response.data.cart);
                    }
                });
                self.cartMaster = response.data.CartMaster;

            })
            .catch(e => { console.log(e) });
        },
        PromoCodeCart: function(cart_id, event) {

            var self = this;
            axios.get("{{route('education.promoCodeCart')}}", {
                params:{
                    cart_id: cart_id,
                    // session_id: session_id,
                    PromoCode: event.target.value
                }
            })
            .then(response => {

                // console.log(response.data);
                if(response.data) {

                    event.target.classList.remove('is-invalid');
                    event.target.classList.add('is-valid');
                    // this.getCartItems();
                }else {
                    event.target.classList.remove('is-valid');
                    event.target.classList.add('is-invalid');
                }
                // vm_this.$set(vm_this.features.total, cart_id, response.data.cart.total);
                self.promo_code = response.data.cart.promo_code;

                self.carts.forEach(function(elem,index){
                    if(elem.id == response.data.cart.id) {
                        // self.carts[index] = response.data.cart;
                        self.$set(self.carts, index, response.data.cart);
                    }
                });
                // vm_this.$set(vm_this.CartMaster, index, response.data.CartMaster);
                // console.log(vm_this.carts[0])


                // vm_this.sub_total = response.data.CartMaster.total;
                // vm_this.vat_value = response.data.CartMaster.vat_value;
                // vm_this.vat_value = response.data.CartMaster.vat_value;
                // vm_this.total_after_vat = response.data.CartMaster.total_after_vat;
            })
            .catch(e => { console.log(e) });
        },
        chackIfExistCart: function(session_id) {
            return this.carts.some(item => item.session_id == session_id)
        },
        addCartItem: function(session_id, type) {

            var self = this;
            axios.get("{{route('education.addToCart')}}", {
                params:{
                    session_id: session_id,
                    type: type,
                }
            })
            .then(response => {

                self.lastCartItem = response.data;
                $('#course_modal').modal('show');
                // self.$set(self.lastCartItem, response.data, null);
                // console.log(self.lastCartItem);
                // if(type == 'wishlist') {
                //     self.deleteWishlistItem(wishlist_id);
                // }else if (type == 'register'){
                //     $('#course_modal').modal('show');
                // }else {
                //     $('#course_modal').modal('show');
                // }
                // self.getCartItems();
            })
            .catch(e => {
                console.log(e)
            });
        },

    }
    });
</script>

</body>

</html>

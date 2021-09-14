
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
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/slick.min.js')}}'></script> --}}
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

<script>
    const Autocomplete = {
        name: "autocomplete",
        template: "#autocomplete",
        props: {
            items: {
                type: Array,
                required: false,
                default: () => []
            },
            isAsync: {
                type: Boolean,
                required: false,
                default: false
            }
        },

        data() {
            return {
                isOpen: false,
                results: [],
                search: "",
                isLoading: false,
                arrowCounter: 0
            };
        },

        methods: {

        },
        watch: {

        },
        mounted() {
        },
        destroyed() {
        }
    };

    window.sessions = {!! $session_array ?? ''!!}
        window.choose_value = {!! json_encode(__('admin.choose_value')) !!}
    var trainingOptionFeatures = {!! json_encode(old('trainingOptionFeatures', [])) !!};

    new Vue({
        el: "#page_content",
        // name: "app",
        // components: {
        //     autocomplete: Autocomplete
        // },
        data: {
            CartWithDetails: @isset($CartWithDetails)@json($CartWithDetails)@else [] @endisset,
            carts: @isset($carts)@json($carts)@else [] @endisset,
            cartMaster: @isset($cartMaster)@json($cartMaster)@else {} @endisset,
            test : 'khaled',
            langCode : '{{app()->getLocale()}}',

            bundle_discount: '{{$bundle_discount??0}}',
            vat: '{{ GetCoinId() == 334 ? 0.15 : 0.00 }}',
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
            ImageUrl: function(img, folder='thumb300'){
                return '{{ CustomAsset("upload") }}/' + folder + '/' + img;
            },
            convertJson : function(data){
                if(this.langCode=='ar'){
                    return JSON.parse(data).ar;
                }
                return JSON.parse(data).en;
                // return JSON.parse(data)
            },
            TotalByCurrency : function(total, total_usd){

                if(this.currency_check != 'SAR'){
                    return total_usd;
                }
                return total;
            },
            chackIfExistWishlist: function(training_option_id) {
                return this.wishlists.some(item => item.training_option_id == training_option_id)
            },
            chackIfExist: function(arrayOfFeatures, feature_id){
                const checkOptionID = obj => obj.training_option_feature_id == feature_id;
                return arrayOfFeatures.some(checkOptionID);
            },
            addCartItem: function(session_id, type) {

                let self = this;
                axios.get("{{route('education.addCartItem')}}", {
                    params:{
                        session_id: session_id,
                        type: type,
                    }
                })
                .then(response => {

                    // console.log(response);
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
            AddCartFeature: function(cart_id, feature_id, price, course_price, event) {

                let self = this;
                axios.get("{{route('education.addCartFeature')}}", {
                    params:{
                        cart_id: cart_id,
                        feature_id: feature_id,
                        price: price,
                        // feature_action: this.features.input[cart_id + '-' + feature_id],
                    }
                }).then(function (response){

                    self.carts.forEach(function(elem,index){
                        if(elem.id == response.data.cart.id) {
                            self.$set(self.carts, index, response.data.cart);
                        }
                    });
                    self.test = 'ali';
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
            deleteCartItem: function(cart_id) {
                if(confirm('Are you Sure?')) {
                    axios.get("{{route('education.deleteCartItem')}}", {
                        params:{
                            cart_id: cart_id
                        }
                    })
                        .then(response => {
                            this.carts = this.carts.filter(function (cart) {
                                  return cart.id != response.data.id;
                            })
                            console.log(response)
                            // this.getCartItems();
                            // this.getCartLater();
                        })
                        .catch(e => { console.log(e) });
                }
            },

        }
    });
</script>



</body>

</html>

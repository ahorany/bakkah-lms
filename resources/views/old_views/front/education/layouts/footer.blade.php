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
            DiscountValue : function(price, discount){

                discount_value = price * (discount / 100);
                return price - discount_value;
            },
            TotalByCurrency : function(total, total_usd){

                if(this.currency_check != 'SAR'){
                    return total_usd;
                }
                return total;
            },
            TotalByCurrency1 : function(cart){

                if(this.currency_check != 'SAR'){
                    return this.DiscountValue(cart.training_option_or_session.price_usd, cart.discount);
                }
                return this.DiscountValue(cart.training_option_or_session.price, cart.discount);
            },
            TotalFeatures : function(cart){

                var price = this.TotalByCurrency(cart.training_option_or_session.price, cart.training_option_or_session.price_usd);
                price = this.DiscountValue(price, cart.discount);

                cart.cart_features.forEach(function(elem, index){
                    price += parseFloat(elem.training_option_feature.price);
                });
                return price;
            },
            chackIfExistWishlist: function(training_option_id) {
                return this.wishlists.some(item => item.training_option_id == training_option_id)
            },
            IsChecked: function(arrayOfFeatures, feature_id){
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
                    self.lastCartItem = response.data.cart;
                    self.CartWithDetails = response.data.CartWithDetails;
                    $('#course_modal').modal('show');
                })
                .catch(e => {
                    console.log(e)
                });
            },
            AddCartFeature: function(cart_id, feature_id, update_cart, event) {

                let self = this;
                axios.get("{{route('education.addCartFeature')}}", {
                    params:{
                        cart_id: cart_id,
                        feature_id: feature_id,
                        update_cart: update_cart,
                    }
                }).then(function (response){

                    if(update_cart==1){
                        self.lastCartItem = response.data.cart;
                    }
                    self.CartWithDetails = response.data.CartWithDetails;
                })
                .catch(e => { console.log(e) });
            },
            PromoCodeCart: function(cart_id, event) {

                let self = this;
                axios.get("{{route('education.promoCodeCart')}}", {
                    params:{
                        cart_id: cart_id,
                        PromoCode: event.target.value
                    }
                })
                .then(response => {

                    self.CartWithDetails = response.data.CartWithDetails;
                    // if(response.data.CartWithDetails) {
                    //     event.target.classList.remove('is-invalid');
                    //     event.target.classList.add('is-valid');
                    // }else {
                    //     event.target.classList.remove('is-valid');
                    //     event.target.classList.add('is-invalid');
                    // }
                    // self.promo_code = response.data.cart.promo_code;
                    // self.carts.forEach(function(elem,index){
                    //     if(elem.id == response.data.cart.id) {
                    //         // self.carts[index] = response.data.cart;
                    //         self.$set(self.carts, index, response.data.cart);
                    //     }
                    // });
                })
                .catch(e => { console.log(e) });
            },
            chackIfExistCart: function(session_id) {
                return this.carts.some(item => item.session_id == session_id)
            },
            deleteCartItem: function(cart_id) {

                if(confirm('Are you Sure?')) {

                    let self = this;
                    axios.get("{{route('education.deleteCartItem')}}", {
                        params:{
                            cart_id: cart_id
                        }
                    })
                    .then(response => {
                        self.CartWithDetails = response.data.CartWithDetails;
                    })
                    .catch(e => { console.log(e) });
                }
            },

        }
    });
</script>

</body>

</html>

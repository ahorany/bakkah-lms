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

                <div class="col-12 mb-2 px-5" v-if="lastCartItem.features.length != 0">
                    <h6 class="boldfont">{{__('education.Features with course')}}</h6>
                </div>

                <div class="col-12 mb-2 px-5" v-for="feature_item in (lastCartItem.features)">
                    <label v-if="feature_item.is_include" class="d-flex justify-content-between m-0">
                        <span class="d-flex align-items-center">
                            <span style="font-size: 26px;width: 30px;"><i class="fas fa-plus-square main-color"></i></span>
                            <span v-html="`${feature_item.title}`"></span>
                        </span>
                        <b v-html="`${feature_item.total_after_vat} ${currency}`"></b>
                    </label>

                    <label v-else class="chk_container d-flex justify-content-between m-0">
                        <span v-html="`${feature_item.title}`"></span>
                        <b v-html="`${feature_item.total_after_vat} ${currency}`"></b>
                        <input type="checkbox" @click="addCartFeature(lastCartItem.cart_id, feature_item.id, feature_item.price, feature_item.total_after_vat, $event)" :checked="feature_item.is_include" value="1"> <span class="checkmark"></span>
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
            onChange() {
                this.$emit("input", this.search);
                if (this.isAsync) {
                    this.isLoading = true;
                } else {
                    if(this.search.length > 2) {
                        this.filterResults();
                        this.isOpen = true;
                    }else {
                        this.isOpen = false;
                    }
                }
            },
            filterResults() {
                axios.get("{{route('education.search-algolia')}}", {
                    params:{
                        q: this.search
                    }
                })
                .then(response => {
                    this.results = response.data;
                })
                .catch(e => { console.log(e); });
            },
            onArrowDown(ev) {
                ev.preventDefault()
                if (this.arrowCounter < this.results.length-1) {
                    this.arrowCounter = this.arrowCounter + 1;
                }else {
                    this.arrowCounter = 0;
                }
                this.fixScrolling();
            },
            onArrowUp(ev) {
                ev.preventDefault()
                if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter - 1;
                }else {
                    this.arrowCounter = this.results.length - 1;
                }
                this.fixScrolling();
            },
            fixScrolling(){
                const liH = this.$refs.options[this.arrowCounter].clientHeight;
                this.$refs.scrollContainer.scrollTop = liH * this.arrowCounter;
            },
            onEnter() {
                var href = '';
                @if(app()->getLocale() == 'en')
                    href = '{{url('/')}}/sessions/'+this.results[this.arrowCounter].slug
                @else
                    href = '{{url('/')}}/ar/sessions/'+this.results[this.arrowCounter].slug
                @endif
                window.location.href = href;
                this.isOpen = false;
                this.arrowCounter = -1;
            },
            showAll() {
                this.isOpen = !this.isOpen;
                (this.isOpen) ? this.results = this.items : this.results = [];
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            }
        },
        watch: {
            items: function(val, oldValue) {
                if (val.length !== oldValue.length) {
                    this.results = val;
                    this.isLoading = false;
                }
            }
        },
        mounted() {
            document.addEventListener("click", this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener("click", this.handleClickOutside);
        }
    };

    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
            sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }

    window.sessions = {!! $session_array ?? ''!!}
    window.choose_value = {!! json_encode(__('admin.choose_value')) !!}

    var trainingOptionFeatures = {!! json_encode(old('trainingOptionFeatures', [])) !!};

    trainingOptionFeatures = sum( trainingOptionFeatures );

    var vm = new Vue({
    el: "#page_content",
    name: "app",
    components: {
        autocomplete: Autocomplete
    },
    data: {
        carts: {!! $carts ?? ''!!},
        details:{!! $details ?? ''!!},
        bundle: '{{$bundle??0}}',
        bundle_discount: '{{$bundle_discount??0}}',
        vat: '{{ GetCoinId() == 334 ? .15 : 0.00 }}',
        // vat_value:125,
        cartTotal: 0,
        vatValue: 0,
        // cartSubTotal: 0,
        currency: '{{ GetCoinId() == 334 ? __("education.SAR") : __("education.USD") }}',
        currency_check: '{{ GetCoinId() == 334 ? "SAR" : "USD" }}',
        lastCartItem: [],
        // langCode: '{{app()->getLocale()}}',
        // selected_session_id:-1,
        // session_choose_value: window.choose_value,
        // val_session_id: '{{request()->val_session_id??null}}',
        // val_course_id: '{{request()->val_course_id??null}}',
        // sessions: window.sessions,
        wishlists: [],
        cartLater: [],

        // Single session register
        @if (strpos(url()->full(), "/register?session_id") !== false)
            price: '{{number_format($SessionHelper->Price(), 2, '.', '')}}',
            Discount: '{{number_format($SessionHelper->Discount(), 2, '.', '')}}',
            IntDiscount: '{{number_format($SessionHelper->Discount(), 2, '.', '')}}',
            DiscountValue: '{{number_format($SessionHelper->DiscountValue(), 2, '.', '')}}',
            ExamPrice: '{{number_format($SessionHelper->ExamPrice(1), 2, '.', '')}}',
            Take2Price: "{{old('take2_option', 0)}}",
            Take2PriceCheck: '{{$SessionHelper->Take2Price()}}',
            Take2PriceOption: "{{old('take2_option', 0)}}",
            ExamSimulation: "{{$SessionHelper->ExamSimulationPrice()}}",
            ExamSimulationPrice: "{{old('ExamSimulationPrice', 0)}}",
            VAT: '{{number_format($SessionHelper->VAT(), 2, '.', '')}}',
            VATVALUE: '{{number_format($SessionHelper->VATFortPriceWithExamPrice(), 2, '.', '')}}',
            ExamIsIncluded: '{{$SessionHelper->ExamIsIncludedWithOld()}}',
            CheckTake2: parseInt('{{$SessionHelper->ExamIsIncludedWithOld()}}') == 0 ? true : false,
            subtotal:'{{number_format($SessionHelper->PriceWithExamPrice(), 2, '.', '')}}',
            PriceAfterDiscountWithExamPriceAfterVAT:'{{number_format($SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT(), 2, '.', '')}}',
            PromoCode:'{{old("promo_code", "")}}',
            ValidPromoCode: false,
            SessionID: '{{request()->session_id}}',
            DiscountId: '{{$session->discount_id??null}}',
            checkedNames: [],
            TrainingOptionPrice: trainingOptionFeatures,
            // RetrivedCode: '{{ old("retrieved_code", "") }}',
            // Balance: 0,
            // Remaining: 0,
            // PaymentRemaining: 0,
            // ValidRetrievedCode: 'no'
        @endif

    },
    mounted () {
        this.getCartItems();
        this.getCartLater();
        this.getWishlistItems();
        this.courseChange(this.val_course_id);
        console.log(trainingOptionFeatures);
        @if (strpos(url()->full(), "/register?session_id") !== false)
        // Single session register
        this.updateValues()
        if(this.PromoCode) {
            const promoCodeInput = document.getElementById('promo_code');
            axios.get("{{route('education.courses.promocode')}}", {
                params:{
                    PromoCode: this.PromoCode,
                    SessionID: this.SessionID
                }
            })
            .then(response => {

                if(response.data) {
                    this.ValidPromoCode = true;
                    promoCodeInput.classList.remove('is-invalid');
                    promoCodeInput.classList.add('is-valid');
                    this.Discount = response.data.value;
                    this.updateCoursePriceAfterPromocodeDiscount();
                    this.updateValues();
                }else {
                    this.ValidPromoCode = false;
                    promoCodeInput.classList.add('is-invalid');
                    promoCodeInput.classList.remove('is-valid');
                    this.Discount = this.IntDiscount;
                    this.updateCoursePriceAfterPromocodeDiscount();
                    this.updateValues();
                }
            })
            .catch(e => {
                // vm.errors.push(e)
            });

        }
        @endif
    },

    @if (strpos(url()->full(), "/register?session_id") !== false)
    watch: {
        ExamIsIncluded:function(){
            if(this.ExamIsIncluded > 0) {
                this.CheckTake2 = false;
            }else {
                this.CheckTake2 = true;
                this.Take2PriceOption = 0;
            }
            this.updateValues();
        }
    },
    @endif

    methods: {
        addCartItem: function(course_id, session_id, type, wishlist_id = null) {

            var self = this;
            axios.get("{{route('education.addToCart')}}", {
                params:{
                    session_id: session_id,
                    type: type,
                    course_id: course_id
                }
            })
            .then(response => {
                self.lastCartItem = response.data.cart;
                if(type == 'wishlist') {
                    self.deleteWishlistItem(wishlist_id);
                }else if (type == 'register'){
                    $('#course_modal').modal('show');
                }else {
                    $('#course_modal').modal('show');
                }
                self.getCartItems();
            })
            .catch(e => {
                console.log(e)
            });
        },
        getCartItems: function() {

            axios.get("{{route('education.cartItems')}}")
            .then(response => {
                // console.log(response.data);
                this.carts = response.data;
                this.cartTotal = 0;
                this.vatValue = 0;
                this.cartSubTotal = 0;
                this.carts.map(cart => {
                    this.cartTotal+=cart.total_after_vat;
                    this.cartSubTotal+=cart.total;
                });
                this.cartSubTotal = this.cartSubTotal.toFixed(2);
                this.cartTotal = this.cartTotal.toFixed(2);
                // this.vatValue = (this.cartSubTotal * this.vat).toFixed(2);
                this.vatValue = parseFloat(this.cartTotal) - parseFloat(this.cartSubTotal);
                this.vatValue = this.vatValue.toFixed(2);
                //this.cartSubTotal = (parseFloat(this.cartTotal) + parseFloat(this.vatValue));
            })
            .catch(e => { console.log(e) });

        },
        deleteCartItem: function(cart_id) {
            if(confirm('Are you Sure?')) {
                axios.get("{{route('education.deleteCartItem')}}", {
                params:{
                    cart_id: cart_id
                    }
                })
                .then(response => {
                    this.getCartItems();
                    this.getCartLater();
                })
                .catch(e => { console.log(e) });
            }
        },
        addCartFeature: function(cart_id, feature_id, price, total_after_vat, event) {

            if (event.target.checked) {
                this.lastCartItem.price += total_after_vat;
            }else {
                this.lastCartItem.price -=  total_after_vat;
            }
            axios.get("{{route('education.addCartFeature')}}", {
                params:{
                    cart_id: cart_id,
                    feature_id: feature_id,
                    price: price,
                }
            })
            .then(response => {
                this.getCartItems();
            })
            .catch(e => { console.log(e) });
        },
        courseChange: function (val) {
            this.session_choose_value = 'loading...';
            axios.get('{{route("crm::rfq.sessionsJson")}}', {
                params: {
                    'course_id' : val
                }
            })
            .then(function(resp){
                this.sessions = resp.data;
                this.session_choose_value = window.choose_value;
            }.bind(this))
            .catch(function(err){
                console.log(err);
            });
        },
        chackIfExist: function(arrayOfFeatures, feature_id) {
            const checkOptionID = obj => obj.training_option_feature_id == feature_id;
            return arrayOfFeatures.some(checkOptionID);
        },
        cartSinglePrice: function(price, cart_features){
            let final_price = 0;
            final_price+=price;
            if(cart_features.length > 0) {
                cart_features.map(feature => final_price+=feature.price)
            }
            return final_price;
        },
        chackIfExistCart: function(session_id) {
            return this.carts.some(item => item.session_id == session_id)
        },
        addWishlistItem: function(training_option_id, session_id) {
            axios.get("{{route('education.addToWishlist')}}", {
                params:{
                    training_option_id: training_option_id,
                    session_id: session_id
                }
            })
            .then(response => {
                this.getWishlistItems();
            })
            .catch(e => { console.log(e) });
        },
        getWishlistItems: function() {
            axios.get("{{route('education.wishlistItems')}}")
            .then(response => {
                this.wishlists = response.data;
            })
            .catch(e => { console.log(e) });
        },
        deleteWishlistItem: function(wishlist_id) {
            axios.get("{{route('education.deleteWishlistItem')}}", {
            params:{
                wishlist_id: wishlist_id
                }
            })
            .then(response => {
                this.getWishlistItems();
            })
            .catch(e => { console.log(e) });
        },
        chackIfExistWishlist: function(training_option_id) {
            return this.wishlists.some(item => item.training_option_id == training_option_id)
        },
        moveToSaveLater: function(cart_id){
            axios.get("{{route('education.moveToLater')}}", {
                params:{
                    cart_id: cart_id
                }
            })
            .then(response => {
                this.getCartItems();
                this.getCartLater();
            })
            .catch(e => { console.log(e) });
        },
        moveToCart: function(cart_id) {
            axios.get("{{route('education.moveToCart')}}", {
                params:{
                    cart_id: cart_id
                }
            })
            .then(response => {
                this.getCartItems();
                this.getCartLater();
            })
            .catch(e => { console.log(e) });
        },
        getCartLater: function() {
            axios.get("{{route('education.cartSaveForLater')}}")
            .then(response => {
                this.cartLater = response.data;
            })
            .catch(e => { console.log(e) });
        },
        PromoCodeCart: function(cart_id, session_id, event) {
            axios.get("{{route('education.promoCodeCart')}}", {
                params:{
                    cart_id: cart_id,
                    session_id: session_id,
                    PromoCode: event.target.value
                }
            })
            .then(response => {
                if(response.data) {
                    event.target.classList.remove('is-invalid');
                    event.target.classList.add('is-valid');
                    this.getCartItems();
                }else {
                    event.target.classList.remove('is-valid');
                    event.target.classList.add('is-invalid');
                }
            })
            .catch(e => { console.log(e) });
        },

        @if (strpos(url()->full(), "/register?session_id") !== false)
        // Session Register Page
        PromoCodeFns: function(e){
            e.preventDefault();
            const promoCodeInput = document.getElementById('promo_code');
            if(vm.PromoCode) {
            axios.get("{{route('education.courses.promocode')}}", {
                params:{
                    PromoCode: vm.PromoCode,
                    SessionID: this.SessionID
                }
            })
            .then(response => {
                if(response.data) {
                    this.ValidPromoCode = true;
                    promoCodeInput.classList.remove('is-invalid');
                    promoCodeInput.classList.add('is-valid');
                    this.Discount = response.data.value;
                    this.updateCoursePriceAfterPromocodeDiscount();
                    this.updateValues();
                }else {
                    this.ValidPromoCode = false;
                    promoCodeInput.classList.add('is-invalid');
                    promoCodeInput.classList.remove('is-valid');
                    this.Discount = this.IntDiscount;
                    this.updateCoursePriceAfterPromocodeDiscount();
                    this.updateValues();
                }
            })
            .catch(e => {
                // vm.errors.push(e)
            });
            }else {
                promoCodeInput.classList.remove('is-valid');
                promoCodeInput.classList.remove('is-invalid');
            }
        },
        // RetrivedCodeFun: function(e) {
        //     e.preventDefault();
        //     const retrieved_code = document.getElementById('retrieved_code');
        //     const email = jQuery('[name="email"]').val();
        //     if(email.length > 0) {
        //         $('#retrieved_code_error').hide();
        //         if(vm.RetrivedCode) {
        //             axios.get("{{route('education.courses.verify_code')}}", {
        //                 params:{
        //                     RetrivedCode: vm.RetrivedCode,
        //                     Email: email
        //                 }
        //             })
        //             .then(response => {
        //                 if(response.data) {
        //                     retrieved_code.classList.remove('is-invalid');
        //                     retrieved_code.classList.add('is-valid');
        //                     this.Balance = parseFloat(response.data);
        //                     this.Remaining = this.PriceAfterDiscountWithExamPriceAfterVAT - this.Balance;
        //                     if(this.Remaining < 0 ) {
        //                         this.Remaining = Math.abs(this.Remaining);
        //                         this.PaymentRemaining = 0;
        //                     }else {
        //                         this.PaymentRemaining = this.Remaining;
        //                         this.Remaining = 0;
        //                     }
        //                     this.ValidRetrievedCode = 'yes';
        //                 }else {
        //                     retrieved_code.classList.add('is-invalid');
        //                     retrieved_code.classList.remove('is-valid');
        //                     this.Balance = this.Remaining = this.PaymentRemaining = 0;
        //                     this.ValidRetrievedCode = 'no';
        //                 }
        //             })
        //             .catch(e => {
        //                 // vm.errors.push(e)
        //             });
        //         }else {
        //             retrieved_code.classList.remove('is-valid');
        //             retrieved_code.classList.remove('is-invalid');
        //         }
        //     }else {
        //         $('#retrieved_code_error').show();
        //     }
        // },
        checkExamSimulation : function(event) {
            if (event.target.checked) {
                this.ExamSimulationPrice = "{{$SessionHelper->ExamSimulationPrice()}}";
            }else {
                this.ExamSimulationPrice = 0;
            }
            this.updateValues();
        },
        addTrainingOption: function(price, event) {
            if (event.target.checked) {
                this.TrainingOptionPrice +=  parseFloat( price );
            }else {
                this.TrainingOptionPrice -= parseFloat( price );
            }
            this.updateValues();

        },
        updateValues: function() {
            var ExamPrice = 0;
            if(this.ExamIsIncluded!=0){
                ExamPrice = this.ExamPrice;
            }

            this.subtotal = (
                parseFloat(this.price) - parseFloat(this.DiscountValue) +
                parseFloat(this.Take2Price) + parseFloat(this.ExamSimulationPrice) + parseFloat(ExamPrice) + parseFloat(this.TrainingOptionPrice)
            ).toFixed(2);

            this.VATVALUE = (parseFloat(this.subtotal) * (this.VAT / 100)).toFixed(2);
            this.PriceAfterDiscountWithExamPriceAfterVAT = (parseFloat(this.subtotal) + parseFloat(this.VATVALUE)).toFixed(2);
        },
        updateCoursePriceAfterPromocodeDiscount: function() {
            this.DiscountValue = (parseFloat(this.price) * this.Discount / 100).toFixed(2);
        }
        @endif
    }
    });
</script>

</body>

</html>

{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/popper.min.js')}}'></script>--}}
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/popper.min.js')}}'></script> --}}
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/bootstrap.min.js')}}'></script> --}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/owl.carousel.min.js')}}'></script>--}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/sliders.js')}}'></script>--}}
{{--<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/jquery.cookie.js')}}'></script>--}}
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/wow.min.js')}}'></script>

<!----------TODO:-------------------->
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/script-main.js')}}'></script> --}}
<!----------TODO:-------------------->
{{-- <script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/slick.min.js')}}'></script> --}}
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/all.js')}}'></script>
{{-- <script src="{{ CustomAsset('js/app.js') }}"></script> --}}
@stack('scripts')

<div id="course_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('education.Added to Cart') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div id="modal-body" class="modal-body" v-if="lastCartItem.length != 0">

            <div class="modal-cart-item row align-items-center">
                <div class="col-8 mb-4 d-flex align-items-center">
                    <i class="fas fa-check"></i>
                    <img class="mx-3" width="80" style="object-fit: contain;" height="64" alt="The Python Mega Course: Build 10 Real World Applications" :src="'/upload/thumb300/' + lastCartItem.course.file">
                    <div class="cart-title boldfont">
                        @{{ langCode == 'en' ? JSON.parse(lastCartItem.course.title).en : JSON.parse(lastCartItem.course.title).ar}}
                    </div>
                </div>
                <div class="col-4 mb-4">
                    <a href="{{route('education.cart')}}" class="btn btn-primary btn-block">{{ __('education.Go to cart') }}</a>
                </div>

                <div class="col-12 mb-3" v-if="lastCartItem.features.length != 0">
                    <h3>{{__('education.Features with course')}}</h3>
                </div>

                <div class="col-12 mb-2" v-for="feature_item in (lastCartItem.features)">
                    <label class="chk_container">
                        @{{feature_item.title}} - @{{feature_item.price}} @{{currency}}
                        <input type="checkbox" @click="addCartFeature(lastCartItem.cart_id, feature_item.id, feature_item.price)" name="mail_subscribe" value="1"> <span class="checkmark"></span>
                    </label>
                </div>

            </div>

        </div>
        </div>
    </div>
</div>

@include(FRONT.'.social_scripts.learning.footer')

</div>

  <script type="text/x-template" id="autocomplete">
    <div class="autocomplete">
        <i class="fas fa-search"></i>
      <input type="text" @input="onChange" v-model="search" @keyup.down="onArrowDown" @keyup.up="onArrowUp" @keyup.enter="onEnter" />
      <ul id="autocomplete-results" v-show="isOpen" ref="scrollContainer" class="autocomplete-results">
        <li class="loading" v-if="isLoading">
          Loading results...
        </li>
        <li ref="options" v-else v-for="(result, i) in results" :key="i" class="autocomplete-result" :class="{ 'is-active': i === arrowCounter }">
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
                        // this.results = response.data.map(re => re.details)
                        this.results = response.data;
                    })
                    .catch(e => {
                        // vm.errors.push(e)
                    });
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
            //this.search = this.results[this.arrowCounter];
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

    var vm = new Vue({
    el: "#page_content",
    name: "app",
    components: {
        // autocomplete: Autocomplete
    },
    data: {
        carts: [],
        cartTotal: 0,
        currency: '{{ GetCoinId() == 334 ? __("education.SAR") : __("education.USD") }}',
        lastCartItem: [],
        langCode: '{{app()->getLocale()}}',
    },
    mounted () {
        // this.getCartItems();
    },
    methods: {
        addCartItem: function(course_id, session_id, type) {
            axios.get("{{route('education.addToCart')}}", {
                params:{
                    session_id: session_id,
                    type: type,
                    course_id: course_id
                }
            })
            .then(response => {
                console.log(response.data.cart);
                this.lastCartItem = response.data.cart
                $('#course_modal').modal('show');
                this.getCartItems();
            })
            .catch(e => { console.log(e) });
        },
        getCartItems: function() {
            axios.get("{{route('education.cartItems')}}")
            .then(response => {
                this.carts = response.data;
                this.cartTotal = 0;
                this.carts.map(cart => this.cartTotal+=cart.price);
            })
            .catch(e => { console.log(e) });
        },
        deleteCartItem: function(cart_id) {
            axios.get("{{route('education.deleteCartItem')}}", {
            params:{
                cart_id: cart_id
                }
            })
            .then(response => {
                this.getCartItems();
            })
            .catch(e => { console.log(e) });
        },
        addCartFeature: function(cart_id, feature_id, price) {
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
        // courseChange: function (val) {
        //     alert();

        //     // this.selected_training_option_id = parseInt(index);
        // },
    }
    });
</script>

</body>

</html>

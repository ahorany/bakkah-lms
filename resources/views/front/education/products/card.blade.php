<div class="course-categories-content" id="course_cards">
    <div class="row justify-content-center mt-5">
        @foreach($courses as $course)
            <div class="course-column col-md-6 col-lg-4 col-xl-3 category_{{$course->category_id}} course_type_{{$course->constant_id}}">
                <div class="exam-box" data-popover-content="#course-{{$course->id}}" data-toggle="popover" data-placement="right">
                    <a href="{{route('education.courses.single', ['slug'=>$course->slug])}}{{$course->option__post_type == 'self-study' ? '/'.$course->option__post_type : ''}}"> <img src="{{CustomAsset('upload/thumb300/'.$course->file)}}" alt="{{GetValueByLang($course->excerpt)}}" title="{{GetValueByLang($course->title)}}"> </a>
                    <div>
                        <h2><a href="{{route('education.courses.single', ['slug'=>$course->slug])}}{{$course->option__post_type == 'self-study' ? '/'.$course->option__post_type : ''}}"> {{GetValueByLang($course->title)}}</a></h2>
                        <p class="course-type">{{GetValueByLang($course->constant_name)}}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="price">
                                {{--
                                @if($constant_id==$discount_training_option_id)
                                    @if(isset($course->discountDetail->discount) && isset($course->trainingOption->session->id))
                                        <small class="bg-primary px-2 mx-1 text-white d-inline-block mb-1">
                                        @if($course->discountDetail->discount->type->slug!='free')
                                            {{$course->discountDetail->discount->value??null}}
                                        @endif
                                        {{__('education.'.$course->discountDetail->discount->type->slug.'_discount')}} </small>
                                        <br>
                                    @endif
                                @endif
                                --}}
                                <?php
                                $SessionHelper->SetCourse($course);
                                $subTotal = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
                                $discount = $SessionHelper->Discount();
                                $main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
                                ?>
                                @if($subTotal!=0)
                                    {{-- {{NumberFormatWithComma($subTotal, 2)}} --}}
                                    {{NumberFormatWithComma($subTotal)}}
                                    <span class="main-color">{{__('education.SAR')}}</span>
                                @endif

                                @if($main_price_before_discount_with_vat!=0 && $main_price_before_discount_with_vat!=$subTotal)
                                    <small class="mx-1 text-secondary" style="text-decoration:line-through;font-size:70%">
                                        {{NumberFormatWithComma($main_price_before_discount_with_vat)}} {{__('education.SAR')}}
                                    </small>
                                @endif

                                @if($discount!=0)
                                    @if ($discount > 0)
                                    <small class="bg-primary px-1 text-white d-inline-block">
                                            @if($discount==100)
                                                {{__('education.free_discount')}}
                                            @else
                                            {{(int)$discount??null}} {{__('education.percentage_discount')}}
                                            @endif
                                    </small>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @include(FRONT.'.education.products.components.exam-included', [
                            'exam_is_included'=>$course->exam_is_included,
                            'exam_price'=>$course->session_exam_price??null,
                        ])

                        @if(Carbon\Carbon::parse($course->created_at)->addDays(30) >= Carbon\Carbon::now())
                            <div class="badge bg-info text-white">{{__("education.New")}}</div>
                        @endif

                        <div class="mt-4">
                            {{--@auth
                                <button @click="addCartItem('{{$course->id}}', '{{$course->session_id}}', 'register')" class="btn btn-secondary btn-block">{{__('education.Buy Now')}}</button>
                            @endauth--}}
                            @guest
                                <a class="btn btn-secondary btn-block" href="{{route('education.courses.single', ['slug'=>$course->slug])}}{{$course->option__post_type == 'self-study' ? '/'.$course->option__post_type : ''}}">{{__('education.Buy Now')}}</a>
                            @endguest

                            <div class="d-flex mt-2">
                                @auth
                                    <button class="btn border btn-fav" @click="addWishlistItem('{{$course->training_option_id}}', '{{$course->session_id}}')">
                                        <i :class="chackIfExistWishlist('{{$course->training_option_id}}') ? 'fas fa-heart main-color' : 'far fa-heart'"></i>
                                    </button>
                                @endauth
                                @guest
                                    <a style="width: auto" class="btn border btn-fav" href="{{route('user.login')}}/?redirect={{url()->current()}}&action=wishlist&option={{$course->training_option_id}}&session_id={{$course->session_id}}" rel="nofollow">
                                        <i class="far fa-heart"></i>
                                    </a>
                                @endguest

                                <span class="mx-1"></span>
                                <button class="btn btn-primary btn-block" @click="addCartItem('{{$course->session_id}}', 'cart')" :disabled="chackIfExistCart('{{$course->session_id}}')">
                                    <template v-if="chackIfExistCart('{{$course->session_id}}')">{{__('education.Already in Cart')}}</template>
                                    <template v-else>{{__('education.Add to Cart')}}</template>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                {{--
                <div id="course-{{$course->id}}" style="display:none;">
                    <div class="popover-body">
                        <div class="popover-sesion-content p-4">
                            <h2>
                                <a href="{{route('education.courses.single', ['slug'=>$course->slug])}}"> {{$course->trans_title}} </a>
                            </h2>
                            @include(FRONT.'.education.Html.rating-stars')
                            @foreach($course->trainingOptions as $trainingOption)
                            @includeWhen(($trainingOption->detail()->where('constant_id', 313)->count()!=0) && $trainingOption->constant_id == 13
                            , FRONT.'.education.courses.single.info.course-includes')
                            @endforeach

                            <a class="btn btn-primary btn-block" href="{{route('education.courses.single', ['slug'=>$course->slug])}}">{{__('education.Buy Now')}}</a>
                        </div>
                    </div>
                </div>
                --}}
            </div> <!-- /.col-md-4 -->
        @endforeach
    </div>
</div>

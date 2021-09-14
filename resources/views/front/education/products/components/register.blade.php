{{--@auth
    <button @click="addCartItem('{{$course->id}}', '{{$course->session_id}}', 'register')" class="btn btn-secondary btn-block">{{__('education.Buy Now')}}</button>
@endauth--}}
@guest
    <a href="{{route('education.courses.register', ['slug'=>$course->slug])}}/?session_id={{$course->session_id}}" class="btn btn-secondary btn-block my-2">{{__('education.Buy Now')}}</a>
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
    <button class="btn btn-primary btn-block" @click="addCartItem('{{$course->id}}', '{{$course->session_id}}', 'cart')" :disabled="chackIfExistCart('{{$course->session_id}}')">{{__('education.Add to Cart')}}</button>
</div>

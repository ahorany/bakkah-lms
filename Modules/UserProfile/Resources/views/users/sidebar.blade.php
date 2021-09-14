<div class="col-md-3 col-lg-2">
    <aside>
        <ul class="list-unstyled p-0 m-0">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> <span class="m-0">{{__('education.Dashboard')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}"><i class="fas fa-home"></i><span> {{__('education.home')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.info') ? 'active' : '' }}" href="{{ route('user.info') }}"><i class="far fa-user"></i> <span> {{__('education.Info')}} </span></a></li>

            {{-- <li><a class="{{ Request::routeIs('user.wishlist') ? 'active' : '' }}" href="{{ route('user.wishlist') }}"><i class="far fa-heart"></i> <span>{{__('education.Wishlists')}} </span></a></li> --}}

            {{-- <li><a class="{{ Request::routeIs('user.notifications') ? 'active' : '' }}" href="{{ route('user.notifications') }}"><i class="far fa-heart"></i> <span>{{__('education.Notifications')}} </span></a></li> --}}

            <li><a class="{{ Request::routeIs('user.my_courses') ? 'active' : '' }}" href="{{ route('user.my_courses') }}"><i class="fas fa-inbox"></i> <span>{{__('education.My Courses')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.certifications') ? 'active' : '' }}" href="{{ route('user.certifications') }}"><i class="far fa-file"></i> <span></span>{{__('education.Certifications')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.payment_info') ? 'active' : '' }}" href="{{ route('user.payment_info') }}"><i class="fas fa-credit-card"></i> <span>{{__('education.Payment_info')}} </span></a></li>

            {{-- <li><a class="{{ Request::routeIs('user.referral') ? 'active' : '' }}" href="{{ route('user.referral') }}"><i class="fas fa-users"></i> <span>{{__('education.Referral')}} </span></a></li> --}}

            {{-- <li><a class="{{ Request::routeIs('user.invoice') ? 'active' : '' }}" href="{{ route('user.invoice') }}"><i class="fas fa-file-invoice"></i> <span>{{__('education.Invoice')}} </span></a></li> --}}

            <li><a class="{{ Request::routeIs('user.request_tickets') ? 'active' : '' }}" href="{{ route('user.request_tickets') }}"><i class="far fa-life-ring"></i> <span>{{__('education.request_tickets')}} </span></a></li>

            {{-- <li><a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> <span>{{__('education.Logout')}} </span></a></li> --}}
        </ul>
    </aside>
    <div class="user-float"><i class="far fa-user"></i></div>
</div>

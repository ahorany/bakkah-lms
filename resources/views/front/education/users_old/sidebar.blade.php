<div class="col-md-3 col-lg-2">
    <aside>
        <ul class="list-unstyled p-0 m-0">
        <li><a class="{{ Request::routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="far fa-star"></i> <span>{{__('education.Dashboard')}} </span></a></li>
            <li><a class="{{ Request::routeIs('user.info') ? 'active' : '' }}" href="{{ route('user.info') }}"><i class="far fa-user"></i> <span>{{__('education.Info')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.notification') ? 'active' : '' }}" href="{{ route('user.notification') }}"><i class="far fa-bell"></i> <span>{{__('education.Notification')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.my_courses') ? 'active' : '' }}" href="{{ route('user.my_courses') }}"><i class="far fa-envelope"></i> <span>{{__('education.My Courses')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.certifications') ? 'active' : '' }}" href="{{ route('user.certifications') }}"><i class="far fa-file"></i> <span>{{__('education.Certifications')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.payment_info') ? 'active' : '' }}" href="{{ route('user.payment_info') }}"><i class="far fa-credit-card"></i> <span>{{__('education.Payment Info')}} </span></a></li>

            <li><a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> <span>{{__('education.Logout')}} </span></a></li>
        </ul>
    </aside>
    <div class="user-float"><i class="far fa-user"></i></div>
</div>

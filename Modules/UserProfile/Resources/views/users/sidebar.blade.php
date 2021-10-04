<div class="col-md-3 col-lg-2">
    <aside>
        <ul class="list-unstyled p-0 m-0">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> <span class="m-0">{{__('education.Dashboard')}} </span></a></li>

            <li><a class="{{ Request::routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}"><i class="fas fa-home"></i><span> {{__('education.home')}} </span></a></li>
            <!-- <li><a class="{{ Request::routeIs('user.info') ? 'active' : '' }}" href="{{ route('user.info') }}"><i class="far fa-user"></i> <span> {{__('education.Info')}} </span></a></li> -->
            <li><a class="{{ Request::routeIs('user.my_courses') ? 'active' : '' }}" href="{{ route('user.my_courses') }}"><i class="fas fa-inbox"></i> <span>{{__('education.My Courses')}} </span></a></li>
            
            <li><a class="{{ Request::routeIs('user.file') ? 'active' : '' }}" href="{{ route('user.file') }}"><i class="fas fa-inbox"></i> <span>{{__('education.File')}} </span></a></li>
            <li><a class="{{ Request::routeIs('user.exercise') ? 'active' : '' }}" href="{{ route('user.exercise') }}"><i class="fas fa-inbox"></i> <span>{{__('education.Exercise')}} </span></a></li>
            <li><a class="{{ Request::routeIs('user.exam') ? 'active' : '' }}" href="{{ route('user.exam') }}"><i class="fas fa-inbox"></i> <span>{{__('education.Exam')}} </span></a></li>


             <li><a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> <span>{{__('education.Logout')}} </span></a></li>
        </ul>
    </aside>
    <div class="user-float"><i class="far fa-user"></i></div>
</div>

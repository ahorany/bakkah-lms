<div class="col-md-3 col-lg-2">
    <aside>
        <ul class="list-unstyled p-0 m-0">
        @foreach($user_sidebar as $item)
            <li><a class="{{ Request::routeIs($item->route_name) ? 'active' : '' }}" href="{{CustomRoute($item->route_name)}}"><i class="fas fa-tachometer-alt"></i> <span class="m-0">{{$item->trans_title}} </span></a></li>
         @endforeach
            @foreach($user_sidebar_courses->courses as $item)
                <li><a class="{{url()->full() == CustomRoute('user.course_details',$item->id)  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id)}}"><i class="fas fa-tachometer-alt"></i> <span class="m-0">{{$item->trans_title}} </span></a></li>
            @endforeach


             <li><a href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt"></i> <span>{{__('education.Logout')}} </span></a></li>
        </ul>
    </aside>
    <div class="user-float"><i class="far fa-user"></i></div>
</div>
